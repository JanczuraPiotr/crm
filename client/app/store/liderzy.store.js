/**
 * @work 2014-10-30 Zamiana response.ret >>> response.code
 * @work 2014-10-30 Dodanie do response tablicy "err" informącej o błędach rozpoznanych indywidualnie dla każdej encji podczas przetwarzania przez BusinessLogic
 * @work 2014-09-24 extjs 4.2.2 >> extjs 5.0.1
 */
Ext.define('LiderzyStore',{
	extend : 'Ext.data.Store',
	xtype : 'liderzy-store',
	alias : 'liderzy-store',
	model : LiderzyModel,
	autoLoad : false,
	autoSync : true,
	autoSave : false,
	idProperty : 'id',

	constructor : function(){
		thisLS = this; // @todo usun zmienną globalną thisLS = this >> var def = this
		thisLS.placowka_id = 0;
		thisLS.superclass.constructor.call(this,arguments);
	},

	listeners : { //LiderzyStore::listenets
		write : function(store,operation,eOpts){
			/**
			 * Odpalane po wykonaniu zapisu na server
			 */
			var resp = Ext.decode(operation.response.responseText);
			switch(operation.action){
				case 'create':
					for(var record in operation.records){
						var recIndex = store.find('tmpId',operation.records[record].data.tmpId);
						var recStore = store.getAt(recIndex);
						recStore.data.id = operation.records[record].data.id;
						delete recStore.data.tmpId;
					}
					break;
				case 'update':

						switch(resp.success){
							case false:
								switch(resp.ret){
									case ERR_EDB_NOTUNIQUE:
										Ext.Msg.alert('Błąd !','Stanowisko może być liderem tylko jednej grupy');
										break;
								}
								thisLS.rejectChanges();
								break;
							case true:
								thisLS.odswierzZespol();
								break;
						}

					break;
			}
		},
		beforesync: function(options,eOpts){
			for(var action in options){
				switch(action){
					case 'create':
						if(thisLS.warunekZapisu(options.create[0].data) === false){
							return false;
						}
						break;
					case 'update':
						var data = null;
						if(options.update !== undefined){
							data = options.update[0].data;
						}
						if(data.tmpId !== undefined || thisLS.warunekZapisu(data) === false){
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
	},//LiderzyStore::liste


	proxy:{ // LiderzyStore::proxy
		type: 'ajax',
		method : 'POST',
		api : {
			create: '../server/ajax/liderzy.php?action=create',
			read: '../server/ajax/liderzy.php?action=read',
			update: '../server/ajax/liderzy.php?action=update',
			destroy: '../server/ajax/liderzy.php?action=delete'
		},

		listeners : { // LiderzyStore::proxy::listeners
			exception: function(proxy, response, operation,eOpts){
				var resp = Ext.decode(response.responseText);
				switch(operation.action){
					case 'create':
					case 'update':

						switch(resp.success){
							case false:
								switch(resp.ret){
									case ERR_EDB_NOTUNIQUE:
										Ext.Msg.alert('Błąd !','Stanowisko może być liderem tylko jednej grupy');
										break;
								}
								thisLS.rejectChanges();
								break;
							case true:
								break;
						}

					case 'destroy':
						switch(resp.success){
							case false:
								switch(resp.ret){
									case ERR_EDB_FOREIGNKEY:
										Ext.Msg.alert('Błąd !','Nie udało się usunąć lidera z powodu zalenych od niej rekordów w bazie');
										break;
									}
								thisLS.rejectChanges();
								break;
						}
						break;
				}

			}
		},// LiderzyStore::proxy::listeners

		writer : { // LiderzyStore::proxy::writer
			writeAllFields : false,
			allowSingle : false,
			rootProperty : 'data',
			getRecordData : function(record,operation){
				return record.data;
			}
		}, // LiderzyStore::proxy::writer

		reader : { // LiderzyStore::proxy::reader
			type : 'json',
			rootProperty : 'data',
			totalProperty : 'countTotal'
		} // LiderzyStore::proxy::reader

	}, // LiderzyStore::proxy

	setPlacowkaId : function(placowka_id){
		var def = this;

		def.placowka_id = placowka_id;
		def.clearFilter();
		def.filter({
			property : 'placowka_id',
			value: placowka_id,
			operator: '='
		});

		if(placowka_id > 0){
			def.load();
		}else{
		}
	},

	warunekZapisu : function(rec){
		if(rec.nazwa === null || rec.symbol === null || rec.data_od === null){
			return false;
		}
		return true;
	},

	odswierzZespol : function(){

	}
});

//var LiderzyStore = Ext.create('LiderzyStore');