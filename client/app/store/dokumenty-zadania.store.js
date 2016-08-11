/**
 * Dokumenty przypisane do zadania.
 *
 * namespace client\app\store
 * use client\app\model\DokumentyZadaniaModel
 *
 * @confirm 2014-11-05
 * @work 2014-10-30 Dodanie do response tablicy "err" informącej o błędach rozpoznanych indywidualnie dla każdej encji podczas przetwarzania przez BusinessLogic
 */
Ext.define('DokumentyZadaniaStore',{
	extend : 'Ext.data.Store',
	model : DokumentyZadaniaModel,
	autoLoad : true,
	autoSync : true,
	autoSave : true,
	remoteFilter : true,
	idProperty : 'id',

	constructor : function(){
		var def = this;
		def.nr_zadania = -1;
		def.callParent(arguments);
	},

	listeners : { //DokumentySprawyStore::listenets
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
	},

	proxy:{
		type: 'ajax',
		method : 'POST',
		api : {
			create: '../server/ajax/dokumenty-zadania.php?action=create',
			read: '../server/ajax/dokumenty-zadania.php?action=read',
			update: '../server/ajax/dokumenty-zadania.php?action=update',
			destroy: '../server/ajax/dokumenty-zadania.php?action=delete'
		},

		listeners : {
			exception: function(proxy, response, operation,eOpts){
				var def = this;
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
								def.rejectChanges();
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
								def.rejectChanges();
								break;
						}
						break;
				}

			}
		},

		writer : {
			writeAllFields : false,
			allowSingle : false,
			rootProperty : 'data',
			getRecordData : function(record,operation){
				return record.data;
			}
		},

		reader : {
			type : 'json',
			rootProperty : 'data',
			totalProperty : 'countTotal'
		}

	},
	setNrZadania : function(nr_zadania){
		var def = this;
		var filter = [];

		if(def.nr_zadania !== nr_zadania){
			def.nr_zadania = nr_zadania;
			if(def.nr_zadania !== 0 ){
				def.clearFilter(true);
				filter.push({
					property : 'nr_zadania',
					operator : '=',
					value    : def.nr_zadania
				});
				def.filter(filter);
			}else{
				def.clearFilter();
			}
		}
	}
});
