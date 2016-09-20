/**
 * @task 2014-10-30 Zamiana response.ret >>> response.code
 * @task 2014-10-30 Dodanie do response tablicy "err" informującej o błędach rozpoznanych indywidualnie dla każdej encji podczas przetwarzania przez BusinessLogic
 * @task 4.2.0
 */
Ext.define('DokumentyProduktuStore',{
	extend : 'Ext.data.Store',
	model : DokumentyProduktuModel,
	autoLoad : false,
	autoSync : true,
	autoSave : false,
	idProperty : 'id',

	listeners : { //DokumentyProduktuStore::listenets
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
	},//DokumentyProduktuStore::liste


	proxy:{ // DokumentyProduktuStore::proxy
		type: 'ajax',
		method : 'POST',
		api : {
			create: '../server/ajax/dokumenty-produktu.php?action=create',
			read: '../server/ajax/dokumenty-produktu.php?action=read',
			update: '../server/ajax/dokumenty-produktu.php?action=update',
			destroy: '../server/ajax/dokumenty-produktu.php?action=delete'
		},

		listeners : { // DokumentyProduktuStore::proxy::listeners
			exception: function(proxy, response, operation,eOpts){
				var def = this;
				var resp = Ext.decode(response.responseText);

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
		},// DokumentyProduktuStore::proxy::listeners

		writer : { // DokumentyProduktuStore::proxy::writer
			writeAllFields : false,
			allowSingle : false,
			rootProperty : 'data',
			getRecordData : function(record,operation){
				return record.data;
			}
		}, // DokumentyProduktuStore::proxy::writer

		reader : { // DokumentyProduktuStore::proxy::reader
			type : 'json',
			rootProperty : 'data',
			totalProperty : 'countTotal'
		} // CDokumentyProduktuStore::proxy::reader

	}, // DokumentyProduktuStore::proxy
	setProduktId : function(produkt_id){
		var def = this;

		def.produkt_id = produkt_id;
		def.clearFilter();
		def.filter({
			property : 'produkt_id',
			value : produkt_id,
			operator : '='
		});
		if(produkt_id > 0){
			def.load();
		}else{
		}

	}
});
