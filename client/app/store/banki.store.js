/**
 * @task 2014-10-30 Zamiana response.ret >>> response.code
 * @task 2014-10-30 Dodanie do response tablicy "err" informącej o błędach rozpoznanych indywidualnie dla każdej encji podczas przetwarzania przez BusinessLogic
 * @task 4.2.0
 */
Ext.define('BankiStore',{
	extend : 'Ext.data.Store',
	model : BankiModel,
	autoLoad : true,
	autoSync : true,
	autoSave : false,
	idProperty : 'id',

	constructor : function(){
		thisBS = this; // @todo usuń zmienną globalną thisBS = this >> var def = this
		thisBS.superclass.constructor.call(this,arguments);
	},

	listeners : { //BankiStore::listenets
		write : function(store,operation,eOpts){
			/**
			 * Odpalane po wykonaniu zapisu na server
			 */
			switch(operation.action){
				case 'create':
					for(var record in operation.records){
						var recIndex = store.find('tmpId',operation.records[record].data.tmpId);
						var recStore = store.getAt(recIndex);
						recStore.data.id = operation.records[record].data.id;
						delete recStore.data.tmpId;
					}
					break;
			}
		},
		beforesync: function(options,eOpts){
			for(var action in options){
				switch(action){
					case 'create':
						var	data = options.create[0].data;
						if(data.symbol === "" || data.nazwa === ''){
							return false;
						}
						break;
					case 'update':
						var data = null;
						if(options.update !== undefined){
							data = options.update[0].data;
						}
						if(data.tmpId !== undefined && data.symbol !== ''){
							// Tworzony jest nowy rekord
							return false;
						}
						if(data.id === 0){
							return false;
						}
						break
				}
			}
		}
	},//BankiStore::liste


	proxy:{ // BankiStore::proxy
		type: 'ajax',
		method : 'POST',
		api : {
			create: '../server/ajax/banki.php?action=create',
			read: '../server/ajax/banki.php?action=read',
			update: '../server/ajax/banki.php?action=update',
			destroy: '../server/ajax/banki.php?action=delete'
		},

		listeners : { // BankiStore::proxy::listeners
			exception: function(proxy, response, operation,eOpts){
				var resp = Ext.decode(response.responseText);

				switch(operation.action){
					case 'create':
					case 'update':
						switch(resp.success){
							case false:
								switch(resp.ret){
									case ERR_EDB_NOTUNIQUE:
										Ext.Msg.alert('Błąd !','<hr>Nie udało się dodać banku. <hr> Prawdopodobny powód : <br> podano symbol lub nazwę zapisane już w bazie, <hr>Proszę nadać unikalne wartości');
										break;
								}
								thisBS.rejectChanges();
								break;
						}

					case 'destroy':
						switch(resp.success){
							case false:
								switch(resp.ret){
									case ERR_EDB_FOREIGNKEY:
										Ext.Msg.alert('Błąd !','Nie udało się usunąć banku z powodu zalenych od niej rekordów w bazie');
										break;
								}
								thisBS.rejectChanges();
								break;
						}
						break;
				}

			}
		},// BankiStore::proxy::listeners

		writer : { // BankiStore::proxy::writer
			writeAllFields : false,
			allowSingle : false,
			rootProperty : 'data',
			getRecordData : function(record,operation){
				return record.data;
			}
		}, // BankiStore::proxy::writer

		reader : { // BankiStore::proxy::reader
			type : 'json',
			rootProperty : 'data',
			totalProperty : 'countTotal'
		} // CBankiStore::proxy::reader

	} // BankiStore::proxy


});

//var BankiStore = new Ext.create('BankiStore');