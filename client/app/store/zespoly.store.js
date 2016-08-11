/**
 * @work 2014-10-30 Zamiana response.ret >>> response.code
 * @work 2014-10-30 Dodanie do response tablicy "err" informącej o błędach rozpoznanych indywidualnie dla każdej encji podczas przetwarzania przez BusinessLogic
 * @work 2014-09-24 extjs 4.2.2 >> extjs 5.0.1
 */
Ext.define('ZespolyStore',{
	extend : 'Ext.data.Store',
	xtype : 'zespoly-store',
	alias : 'zespoly-store',
	model : ZespolyModel,
	autoLoad : false,
	autoSync : true,
	autoSave : false,
	idProperty : 'id',

	constructor : function(){
		var def = this;
		def.lider_id = -1;
		def.firma_id = -1;
		def.firma_oddzial_id = -1;

		Ext.apply(def,{
			listeners : { //ZespolyStore::listenets
				write : function(store,operation,eOpts){
					var resp = Ext.decode(operation.response.responseText);
					switch(operation.action){
						case 'create':
							console.log('::create');
							switch (resp.success){
								case true:
									for(var record in operation.records){
										var recIndex = store.find('tmpId',operation.records[record].data.tmpId);
										var recStore = store.getAt(recIndex);
										recStore.data.id = operation.records[record].data.id;
										delete recStore.data.tmpId;
									}
									break;
								case false:
									switch(resp.ret){
										case ERR_EDB_NOTUNIQUE:
											Ext.Msg.alert('Błąd !','Próba ponownego dodania stanowiska do zespołu');
											def.rejectChanges();
											break;
									}
									break;
							};
							break;
						case 'read':
							break;
						case 'update':
							console.log('::update');
							switch(resp.success){
								case true:
									break;
								case false:
									switch(resp.ret){
										case ERR_EDB_NOTUNIQUE:
											Ext.Msg.alert('Błąd !','Próba ponownego dodania stanowiska do zespołu');
											def.rejectChanges();
											break;
									}
									break;
							}
							break;
						case 'destroy':
							console.log(store);
							console.log(operation);
							break;
					}
				},
				beforesync: function(options,eOpts){
					for(var action in options){
						switch(action){
							case 'create':
								if(def.warunekZapisu(options.create[0].data) === false){
									return false;
								}
								break;
							case 'read':
								console.log('::read');
								break;
							case 'update':
								console.log('::update');
								var data = null;
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
							case 'destroy':
								console.log('::destroy');
								break;
						}
					}
				}
			},//ZespolyStore::liste
			proxy:{ // ZespolyStore::proxy
				type: 'ajax',
				method : 'POST',
				api : {
					create: '../server/ajax/zespoly.php?action=create',
					read: '../server/ajax/zespoly.php?action=read',
					update: '../server/ajax/zespoly.php?action=update',
					destroy: '../server/ajax/zespoly.php?action=delete'
				},

				listeners : { // ZespolyStore::proxy::listeners
					exception: function(proxy, response, operation,eOpts){
						var resp = Ext.decode(response.responseText);
						switch(operation.action){
							case 'create':
							case 'update':
								switch(resp.success){
									case false:
										switch(resp.ret){
											case ERR_EDB_NOTUNIQUE:
												Ext.Msg.alert('Błąd !','Próba ponownego dodania stanowiska do zespołu');
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
												Ext.Msg.alert('Błąd !','Nie udało się usunąć zespoly z powodu zalenych od niej rekordów w bazie');
												break;
											}
										def.rejectChanges();
										break;
								}
								break;
						}

					}
				},// ZespolyStore::proxy::listeners

				writer : { // ZespolyStore::proxy::writer
					writeAllFields : false,
					allowSingle : false,
					rootProperty : 'data',
					getRecordData : function(record,operation){
						return record.data;
					}
				}, // ZespolyStore::proxy::writer

				reader : { // ZespolyStore::proxy::reader
					type : 'json',
					rootProperty : 'data',
					totalProperty : 'countTotal'
				} // ZespolyStore::proxy::reader

			} // ZespolyStore::proxy
		});


		def.superclass.constructor.call(def,arguments);
	},
	warunekZapisu : function(rec){
		if(rec.lider_id === null || rec.stanowisko_id === null ){
			return false;
		}
		return true;
	},
	setFirmaOddzial : function(firma_id,firma_oddzial_id){
		var def = this;
		def.firma_id = firma_id;
		def.firma_oddzial_id = firma_oddzial_id;
	},
	setLiderId : function(lider_id){
		var def = this;

		def.lider_id = lider_id;
		def.clearFilter();
		def.filter({
			'property' : 'lider_id',
			'value' : lider_id,
			'operator' : '='
		});

		if(lider_id > 0){
			def.load();
		}else{
		}
	}
});
