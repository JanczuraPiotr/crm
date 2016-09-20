/**
 * Wyświetla wszystkie kroki zadania, którego numer przekazywany jest za pomocą funkcji setNrZadania(nr_zadania)
 * @task 2014-10-30 Dodanie do response tablicy "err" informącej o błędach rozpoznanych indywidualnie dla każdej encji podczas przetwarzania przez BusinessLogic
 * @task 4.2.0
 * namespace client\app\store
 */
Ext.define('KrokiZadaniaStore',{
	extend : 'Ext.data.Store',
	model : KrokiZadaniaModel,
	autoLoad : false,
	autoSync : false,
	autoSave : false,
	remoteFilter : true,
	idProperty : 'id',

	constructor : function(config){
		var def = this;
		def.callParent(arguments);
		def.setNrZadania( config && ( config.nr_zadania || 0 ) );
	},
	listeners : {
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
				var data = null;
				if(def.nr_zadania === 0){
					return false;
				}
				for(var action in options){
					switch(action){
						case 'create':
							if(def.warunekZapisu(options.create[0].data) === false){
								return false;
							}
							break;
						case 'update':
							data = null;
							if(options.update !== undefined){
								data = options.update[0].data;
							}
							if(data.tmpId !== undefined || def.warunekZapisu(data) === false){
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
	},//listeners

	proxy : {
		type: 'ajax',
		method : 'POST',
		api : {
			create: '../server/ajax/kroki-zadania.php?action=create',
			read: '../server/ajax/kroki-zadania.php?action=read',
			update: '../server/ajax/kroki-zadania.php?action=update',
			destroy: '../server/ajax/kroki-zadania.php?action=delete'
		},

		listeners : {
			exception: function(proxy, response, operation,eOpts){
				var resp = Ext.decode(response.responseText);
				switch(operation.action){
					case 'create':
					case 'update':
						switch(resp.success){
							case false:
								switch(resp.ret){
									case ERR_EDB_NOTUNIQUE:
										Ext.Msg.alert('Błąd !',resp.msg);
										//Ext.Msg.alert('Błąd !','<hr>Nie udało się dodać kroki-zadania. <hr> Prawdopodobny powód : <br> podano symbol lub nazwę zapisane już w bazie, <hr>Proszę nadać unikalne wartości');
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
										Ext.Msg.alert('Błąd !','Nie udało się usunąć kroki-zadania z powodu zalenych od niej rekordów w bazie');
										break;
									}
								def.rejectChanges();
								break;
						}
						break;
				}

			}
		},// KrokiZadaniaStore::proxy::listeners

		writer : {
			writeAllFields : false,
			allowSingle : false,
			rootProperty : 'data',
			getRecordData : function(record,operation){
				return record.data;
			}
		}, // proxy::writer

		reader : {
			type : 'json',
			rootProperty : 'data',
			totalProperty : 'countTotal'
		} // proxy::reader

	}, // proxy


	setNrZadania : function(nr_zadania){
		var def = this;
		if(def.nr_zadania !== nr_zadania){
			def.nr_zadania = nr_zadania;
			if(def.nr_zadania > 0){
				def.clearFilter(true);
				def.filter({
					property : 'nr_zadania',
					value: nr_zadania,
					operator: '='
				});
			}else{
				def.clearFilter(true);
			}
		}
	},
	warunekZapisu : function(rec){
		return true;
	}
});