/**
 * @task 2014-10-30 Zamiana response.ret >>> response.code
 * @task 2014-10-30 Dodanie do response tablicy "err" informującej o błędach rozpoznanych indywidualnie dla każdej encji podczas przetwarzania przez BusinessLogic
 * @work 4.2.0
 */
Ext.define('CRM.store.StatusKlienta',{
	extend : 'Ext.data.Store',
	model : 'CRM.model.StatusKlienta',
	autoLoad : true,
	autoSync : true,
	autoSave : false,
	idProperty : 'id',

	listeners : { //PochodzenieklientaStore::listenets
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
						if( thisSKS.warunekZapisu(data) === false){
							return false;
						}
						break;
					case 'read':
						break;
					case 'update':
						var data = null;
						if(options.update !== undefined){
							data = options.update[0].data;
						}
						if(data.tmpId !== undefined && thisSKS.warunekZapisu(data) === false ){
							// Tworzony jest nowy rekord
							return false;
						}
						if(data.id === 0){
							return false;
						}
						break
					case 'destroy':
						break;
				}
			}
		}
	},//PochodzenieklientaStore::liste


	proxy:{ // PochodzenieklientaStore::proxy
		type: 'ajax',
		method : 'POST',
		api : {
			create: '../server/ajax/status-klienta.php?action=create',
			read: '../server/ajax/status-klienta.php?action=read',
			update: '../server/ajax/status-klienta.php?action=update',
			destroy: '../server/ajax/status-klienta.php?action=delete'
		},

		listeners : { // PochodzenieKlientaStore::proxy::listeners
			exception: function(proxy, response, operation,eOpts){
				/*
				 * response.responseText - zawiera całą treść zwróconą przez serwer
				 */
				var resp = Ext.decode(response.responseText);

				switch(operation.action){
					case 'create':
					case 'update':
						switch(resp.success){
							case false:
								switch(resp.ret){
									case ERR_EDB_NOTUNIQUE:
										Ext.Msg.alert('Błąd !','<hr>Nie udało się rekordu. <hr> Prawdopodobny powód : <br> podano wartości zapisane już w bazie, <hr>Proszę nadać unikalne wartości');
										break;
								}
								thisSKS.rejectChanges();
								break;
						}

					case 'destroy':
						switch(resp.success){
							case false:
								switch(resp.ret){
									case ERR_EDB_FOREIGNKEY:
										Ext.Msg.alert('Błąd !','Nie udało się usunąć rekordu z powodu zalenych od niej rekordów w bazie');
										break;
								}
								thisSKS.rejectChanges();
								break;
						}
						break;
				}

			}
		},// PochodzenieKlientaStore::proxy::listeners

		writer : { // PochodzenieklientaStore::proxy::writer
			writeAllFields : false,
			allowSingle : false,
			rootProperty : 'data',
			getRecordData : function(record,operation){
				return record.data;
			}
		}, // PochodzenieklientaStore::proxy::writer

		reader : { // PochodzenieklientaStore::proxy::reader
			type : 'json',
			rootProperty : 'data',
			totalProperty : 'countTotal'
		} // CPochodzenieklientaStore::proxy::reader

	}, // PochodzenieklientaStore::proxy

	warunekZapisu : function(rec){
		if(rec.symbol !== '' && rec.status !== '' && rec.opis !== ''){
			return true;
		}else{
			return false;
		}
	}
});