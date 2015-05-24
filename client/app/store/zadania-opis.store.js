/**
 * @confirm 2015-01-02 ExtJS 5.1.0
 */
Ext.define('ZadaniaOpisStore',{
	extend : 'Ext.data.Store',
	model : ZadaniaOpisModel,
	autoLoad : true,
	autoSync : true,
	autoSave : true,
	remoteFilter : true,
	idProperty : 'id',

	constructor : function(){
		var def = this;
		def.nr_zadania = 0;
		def.callParent(arguments);
		def.proxy.def = def;
	},

	listeners : { //ZadaniaOpisStore::listenets
		write : function(store,operation,eOpts){
			var record,
					records,
					recIndex,
					recStore;
			/**
			 * Odpalane po wykonaniu zapisu na server
			 */
			switch(operation.getRequest().getAction()){
				case 'create':
					console.log('ZadaniaOpisStore->listenets->write');
					records = operation.getRecords();
					for(var record in records){
						recIndex = store.find('tmpId',records[record].data.tmpId);
						recStore = store.getAt(recIndex);
						recStore.data.id = records[record].data.id;
						delete recStore.data.tmpId;
					}
					break;
			}
		},
		beforesync: function(options,eOpts){
			console.log('ZadaniaOpisStore->beforesync');
			var def = this;
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
						var data = null;
						if(options.update !== undefined){
							data = options.update[0].data;
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
	},//ZadaniaOpisStore::listeners
	proxy : { // ZadaniaOpisStore::proxy
			type: 'ajax',
			method : 'POST',
			api : {
				create: '../server/ajax/zadania-opis.php?action=create',
				read: '../server/ajax/zadania-opis.php?action=read',
				update: '../server/ajax/zadania-opis.php?action=update'
//					destroy: '../server/ajax/zadania-opis.php?action=delete'
			},

			listeners : { // ZadaniaOpisStore::proxy::listeners
				exception: function(proxy, response, operation,eOpts){
					var def = proxy.def;
					var resp = Ext.decode(response.responseText);
					var key;
					switch(operation.action){
						case 'create':
						case 'update':
							if(resp.code === E.code.OK){
								for(key in resp.err){
									switch(resp.err[key].code){
										default :
											Ext.Msg.alert('Błąd !',resp.msg)
									}
								}
							}else{
								Ext.Msg.alert('Błąd !',resp.msg);
							}
							break;
					}

				}
			},// ZadaniaOpisStore::proxy::listeners

			writer : { // ZadaniaOpisStore::proxy::writer
				writeAllFields : false,
				allowSingle : false,
				rootProperty : 'data'
			}, // ZadaniaOpisStore::proxy::writer

			reader : { // ZadaniaOpisStore::proxy::reader
				type : 'json',
				rootProperty : 'data',
				totalProperty : 'countTotal'
			} // ZadaniaOpisStore::proxy::reader
	}, // ZadaniaOpisStore::proxy

	setNrZadania : function(nr_zadania){
		var def = this;

		if(def.nr_zadania !== nr_zadania){
			def.nr_zadania = nr_zadania;

			if(nr_zadania > 0 && nr_zadania !== null){
				def.clearFilter(true);
				def.filter({
					property : 'nr_zadania',
					value : nr_zadania,
					operator : '='
				});
			}else{
				def.clearFilter();
			}
		}
	},
	warunekZapisu : function(rec){
		return true;
	}
});