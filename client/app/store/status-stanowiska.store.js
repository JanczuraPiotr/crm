/**
 * @work 2014-10-30 Dodanie do response tablicy "err" informącej o błędach rozpoznanych indywidualnie dla każdej encji podczas przetwarzania przez BusinessLogic
 */
Ext.define('StatusStanowiskaStore',{
	extend : 'Ext.data.Store',
	model : StatusStanowiskaModel,
	autoLoad : true,
	autoSync : true,
	autoSave : false,
	idProperty : 'id',

	constructor : function(){
		thisSSS = this; // @todo usuń zmienną globalną thisSSS = this >> var def = this
		thisSSS.superclass.constructor.call(this,arguments);
	},

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
						if( thisSSS.warunekZapisu(data) === false){
							return false;
						}
						break;
					case 'update':
						var data = null;
						if(options.update !== undefined){
							data = options.update[0].data;
						}
						if(data.tmpId !== undefined && thisSSS.warunekZapisu(data) === false ){
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
	},//PochodzenieklientaStore::liste


	proxy:{ // PochodzenieklientaStore::proxy
		type: 'ajax',
		method : 'POST',
		api : {
			create: '../server/ajax/status-stanowiska.php?action=create',
			read: '../server/ajax/status-stanowiska.php?action=read',
			update: '../server/ajax/status-stanowiska.php?action=update',
			destroy: '../server/ajax/status-stanowiska.php?action=delete'
		},

		listeners : { // StatusStanowiskaStore::proxy::listeners
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
								thisSSS.rejectChanges();
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
								thisSSS.rejectChanges();
								break;
						}
						break;
				}

			}
		},// StatusStanowiskaStore::proxy::listeners

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
		if(rec.symbol !== '' && rec.opis !== ''){
			return true;
		}else{
			return false;
		}
	}
});
