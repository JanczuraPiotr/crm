/**
 * @task 2014-10-30 Zamiana response.ret >>> response.code
 * @task 2014-10-30 Dodanie do response tablicy "err" informującej o błędach rozpoznanych indywidualnie dla każdej encji podczas przetwarzania przez BusinessLogic
 * @work 4.2.0
 */
Ext.define('CRM.store.PochodzenieKlientow',{
	extend : 'Ext.data.Store',
	model : 'CRM.model.PochodzenieKlientow',
	autoLoad : true,
	autoSync : true,
	autoSave : false,
	idProperty : 'id',

	constructor : function(){
//		thisSDS.superclass.constructor.call(this,arguments);
		this.callParent(arguments);
		this.proxy.def = this;
	},

	listeners : { //PochodzenieklientowStore::listenets
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
						if(data.symbol === "" || data.opis === ''){
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
						break;
				}
			}
		}
	},//PochodzenieklientowStore::liste


	proxy:{ // PochodzenieklientowStore::proxy
		type: 'ajax',
		method : 'POST',
		api : {
			create: '../server/ajax/pochodzenie-klientow.php?action=create',
			read: '../server/ajax/pochodzenie-klientow.php?action=read',
			update: '../server/ajax/pochodzenie-klientow.php?action=update',
			destroy: '../server/ajax/pochodzenie-klientow.php?action=delete'
		},

		listeners : { // PochodzenieklientowStore::proxy::listeners
			exception: function(proxy, response, operation,eOpts){
				// response.responseText - zawiera całą treść zwróconą przez serwer
				var resp = Ext.decode(response.responseText);
				var def = proxy.def;
				switch(operation.action){
					case 'create':
					case 'update':
						switch(resp.success){
							case false:
								switch(resp.ret){
									case ERR_EDB_NOTUNIQUE:
										Ext.Msg.alert('Błąd !','<hr>Nie udało się dodać dokumentu. <hr> Prawdopodobny powód : <br> podano symbol lub nazwę zapisane już w bazie, <hr>Proszę nadać unikalne wartości');
										break;
								}
								def.rejectChanges();
								break;
						}

					case 'destroy':
						switch(resp.success){
							case false:
								switch(resp.ret){
									case ERR_EDB_FOREIGNKEY:
										Ext.Msg.alert('Błąd !','Nie udało się usunąć dokumentu z powodu zalenych od niej rekordów w bazie');
										break;
								}
								def.rejectChanges();
								break;
						}
						break;
				}

			}
		},// PochodzenieklientowStore::proxy::listeners

		writer : { // PochodzenieklientowStore::proxy::writer
			writeAllFields : false,
			allowSingle : false,
			root : 'data',
			getRecordData : function(record,operation){
				return record.data;
			}
		}, // PochodzenieklientowStore::proxy::writer

		reader : { // PochodzenieklientowStore::proxy::reader
			type : 'json',
			root : 'data',
			total : 'countTotal'
		} // CPochodzenieklientowStore::proxy::reader

	} // PochodzenieklientowStore::proxy


});
