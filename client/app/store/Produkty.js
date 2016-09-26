	/**
 * @task 2014-10-30 Zamiana response.ret >>> response.code
 * @task 2014-10-30 Dodanie do response tablicy "err" informującej o błędach rozpoznanych indywidualnie dla każdej encji podczas przetwarzania przez BusinessLogic
 * @work 4.2.0
 */
Ext.define('CRM.store.Produkty',{
	extend : 'Ext.data.Store',
	model : 'CRM.model.Produkty',
	autoLoad : false,
	autoSync : true,
	autoSave : false,
	idProperty : 'id',

	constructor : function(){
		var def = this; // @todo usun zmienną globalną thisPS = this >> var def = this
		def.bank_id = 0;
//		def.callParent(arguments);
		def.superclass.constructor.call(this,arguments);
	},

	listeners : { //ProduktyStore::listenets
		write : function(store,operation,eOpts){
			var listener = this;
			switch(operation.action){
				case 'create':
					for(var record in operation.records){
						var recIndex = store.find('tmpId',operation.records[record].data.tmpId);
						var recStore = store.getAt(recIndex);
						recStore.data.id = operation.records[record].data.id;
						delete recStore.data.tmpId;
					}
					listener.onWriteCreate(store,operation,eOpts);
					break;
				case 'read':
					break;
				case 'update':
					break;
				case 'destroy':
					break;
			}
		},
		beforesync: function(options,eOpts){
			var listener = this;
			if(listener.bank_id === 0){
				console.log('CRM.store.Produkty::listeners::beforesync -> podłącz produkt do banku');
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
	},//ProduktyStore::liste


	proxy:{ // ProduktyStore::proxy
		type: 'ajax',
		method : 'POST',
		api : {
			create: '../server/ajax/produkty.php?action=create',
			read: '../server/ajax/produkty.php?action=read',
			update: '../server/ajax/produkty.php?action=update',
			destroy: '../server/ajax/produkty.php?action=delete'
		},

		listeners : { // ProduktyStore::proxy::listeners
			exception: function(proxy, response, operation,eOpts){
				var resp = Ext.decode(response.responseText);

				switch(operation.action){
					case 'create':
					case 'update':
						switch(resp.success){
							case false:
								switch(resp.ret){
									case ERR_EDB_NOTUNIQUE:
										Ext.Msg.alert('Błąd !','<hr>Nie udało się dodać banku. <hr> Prawdopodobny powód : <br> podano symbol lub nazwę zapisane już w bazie, <hr>Proszę nadać unikalne wartości');
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
										Ext.Msg.alert('Błąd !','Nie udało się usunąć banku z powodu zalenych od niej rekordów w bazie');
										break;
								}
								def.rejectChanges();
								break;
						}
						break;
				}

			}
		},// ProduktyStore::proxy::listeners

		writer : { // ProduktyStore::proxy::writer
			writeAllFields : false,
			allowSingle : false,
			root : 'data',
			getRecordData : function(record,operation){
				return record.data;
			}
		}, // ProduktyStore::proxy::writer

		reader : { // ProduktyStore::proxy::reader
			type : 'json',
			root : 'data',
			total : 'countTotal'
		} // CProduktyStore::proxy::reader

	}, // ProduktyStore::proxy

	onWriteCreate : function(store,operation,eOpts){
		var def = this;
		console.log('CRM.store.Produkty::onWriteCreate');
	},
	setBankId : function(bank_id){
		var def = this;
		def.bank_id = bank_id;
		if(bank_id > 0){
			def.clearFilter();
			def.filter({
				property: 'bank_id',
				value: bank_id,
				operator: '='
			});
			def.load();
		}else{
			def.clearFilter();
			def.filter({
				property: 'bank_id',
				value: bank_id,
				operator: '='
			});
		}
	}

});

//var ProduktyStore = new Ext.create('ProduktyStore');