/**
 * @prace 2014-10-30 Zamiana response.ret >>> response.code
 * @prace 2014-10-30 Dodanie do response tablicy "err" informącej o błędach rozpoznanych indywidualnie dla każdej encji podczas przetwarzania przez BusinessLogic
 * @prace 2014-09-24 extjs 4.2.2 >> extjs 5.0.1
 */
Ext.define('BankiOddzialyStore',{
	extend : 'Ext.data.Store',
	model : BankiOddzialyModel,
	autoLoad : false,
	autoSync : true,
	autoSave : false,
	idProperty : 'id',

	constructor : function(){
		thisBOS = this; // @todo usuń zmienną globalną thisBOS = this >> var def = this
		thisBOS.bank_id = 0;
		thisBOS.superclass.constructor.call(this,arguments);
	},

	listeners : { //BankiOddzialyStore::listenets
		write : function(store,operation,eOpts){
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
			var thisBOS = this;
			if(thisBOS.bank_id === 0){
				return false;
			}
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
	},//BankiOddzialyStore::liste


	proxy:{ // BankiOddzialyStore::proxy
		type: 'ajax',
		method : 'POST',
		api : {
			create: '../server/ajax/banki-oddzialy.php?action=create',
			read: '../server/ajax/banki-oddzialy.php?action=read',
			update: '../server/ajax/banki-oddzialy.php?action=update',
			destroy: '../server/ajax/banki-oddzialy.php?action=delete'
		},

		listeners : { // BankiOddzialyStore::proxy::listeners
			exception: function(proxy, response, operation,eOpts){
				var resp = Ext.decode(response.responseText);
				switch(operation.action){
					case 'create':
					case 'update':
						switch(resp.success){
							case false:
								switch(resp.ret){
									case ERR_EDB_NOTUNIQUE:
										Ext.Msg.alert('Błąd !','<hr>Nie udało się dodać oddziału banku. <hr> Prawdopodobny powód : <br> podano symbol lub nazwę zapisane już w bazie, <hr>Proszę nadać unikalne wartości');
										break;
								}
								thisBOS.rejectChanges();
								break;
						}

					case 'destroy':
						switch(resp.success){
							case false:
								switch(resp.ret){
									case ERR_EDB_FOREIGNKEY:
										Ext.Msg.alert('Błąd !','Nie udało się usunąć oddzialu banku z powodu zalenych od niej rekordów w bazie');
										break;
									}
								thisBOS.rejectChanges();
								break;
						}
						break;
				}

			}
		},// BankiOddzialyStore::proxy::listeners

		writer : { // BankiOddzialyStore::proxy::writer
			writeAllFields : false,
			allowSingle : false,
			rootProperty : 'data',
			getRecordData : function(record,operation){
				return record.data;
			}
		}, // BankiOddzialyStore::proxy::writer

		reader : { // BankiOddzialyStore::proxy::reader
			type : 'json',
			rootProperty : 'data',
			totalProperty : 'countTotal'
		} // BankiOddzialyStore::proxy::reader

	}, // BankiOddzialyStore::proxy

	setBankId : function(bank_id){
		this.bank_id = bank_id;
	}

});

var BankiOddzialyStore = Ext.create('BankiOddzialyStore');