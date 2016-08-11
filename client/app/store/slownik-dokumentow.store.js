/**
 * Slownik wszystkich snanych w systemie dokumentów.
 *
 * namespace client\app\store
 * use client\app\model\SlownikDokumentowModel
 * 
 * @confirm 2014-12-22
 * @work 2014-10-30 Dodanie do response tablicy "err" informącej o błędach rozpoznanych indywidualnie dla każdej encji podczas przetwarzania przez BusinessLogic
 */
Ext.define('SlownikDokumentowStore',{
	extend : 'Ext.data.Store',
	model : SlownikDokumentowModel,
	autoLoad : true,
	autoSync : true,
	autoSave : false,
	idProperty : 'id',

	constructor : function(){
		thisSDS = this; // @todo usun zmienna globalna thisSDS = this >> var def = this
		thisSDS.superclass.constructor.call(this,arguments);
	},

	listeners : { //SlownikDokumentowStore::listenets
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
	},//SlownikDokumentowStore::liste


	proxy:{ // SlownikDokumentowStore::proxy
		type: 'ajax',
		method : 'POST',
		api : {
			create: '../server/ajax/slownik-dokumentow.php?action=create',
			read: '../server/ajax/slownik-dokumentow.php?action=read',
			update: '../server/ajax/slownik-dokumentow.php?action=update',
			destroy: '../server/ajax/slownik-dokumentow.php?action=delete'
		},

		listeners : { // SlownikDokumentowStore::proxy::listeners
			exception: function(proxy, response, operation,eOpts){
				var resp = Ext.decode(response.responseText);

				switch(operation.action){
					case 'create':
					case 'update':
						switch(resp.success){
							case false:
								switch(resp.code){
									case ERR_EDB_NOTUNIQUE:
										Ext.Msg.alert('Błąd !','<hr>Nie udało się dodać dokumentu. <hr> Prawdopodobny powód : <br> podano symbol lub nazwę zapisane już w bazie, <hr>Proszę nadać unikalne wartości');
										break;
								}
								thisSDS.rejectChanges();
								break;
						}

					case 'destroy':
						switch(resp.success){
							case false:
								switch(resp.code){
									case ERR_EDB_FOREIGNKEY:
										Ext.Msg.alert('Błąd !','Nie udało się usunąć dokumentu z powodu zalenych od niej rekordów w bazie');
										break;
								}
								thisSDS.rejectChanges();
								break;
						}
						break;
				}

			}
		},// SlownikDokumentowStore::proxy::listeners

		writer : { // SlownikDokumentowStore::proxy::writer
			writeAllFields : false,
			allowSingle : false,
			rootProperty : 'data',
			getRecordData : function(record,operation){
				return record.data;
			}
		}, // SlownikDokumentowStore::proxy::writer

		reader : { // SlownikDokumentowStore::proxy::reader
			type : 'json',
			rootProperty : 'data',
			totalProperty : 'countTotal'
		} // CSlownikDokumentowStore::proxy::reader

	} // SlownikDokumentowStore::proxy


});
