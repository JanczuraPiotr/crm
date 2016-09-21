/**
 * @task 2014-10-30 Zamiana response.ret >>> response.code
 * @task 2014-10-30 Dodanie do response tablicy "err" informującej o błędach rozpoznanych indywidualnie dla każdej encji podczas przetwarzania przez BusinessLogic
 * @done 4.2.0
 */
Ext.define('ZarzadStore',{
	extend : 'Ext.data.Store',
	model : ZarzadModel,
	autoLoad : false,
	autoSync : true,
	autoSave : false,
	idProperty : 'id',

	constructor : function(){
		this.firma_id = 0;
		this.superclass.constructor.call(this,arguments);
	},

	listeners : { //ZarzadStore::listenets
		write : function(store,operation,eOpts){
			var thisZS = this;
			/**
			 * Odpalane po wykonaniu zapisu na server
			 */
			console.log('ZarzadStore::listeners::write');
			console.log(operation);
			switch(operation.action){
				case 'create':
					for(var record in operation.records){
						var recIndex = store.find('tmpId',operation.records[record].data.tmpId);
						var recStore = store.getAt(recIndex);
						recStore.data.id = operation.records[record].data.id;
						delete recStore.data.tmpId;
					}
					break;
				case 'read':
					break;
				case 'update':
					break;
				case 'destroy':
					console.log(store);
					console.log(operation);
					break;
			}
		},
		beforesync: function(options,eOpts){
			var def = this;
			console.log('ZarzadStore::listeners::beforesync');
			console.log(options);
			if(def.firma_id === 0){
				return false;
			}
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
	},//ZarzadStore::liste


	proxy:{ // ZarzadStore::proxy
		type: 'ajax',
		method : 'POST',
		api : {
			create: '../server/ajax/zarzad.php?action=create',
			read: '../server/ajax/zarzad.php?action=read',
			update: '../server/ajax/zarzad.php?action=update',
			destroy: '../server/ajax/zarzad.php?action=delete'
		},

		listeners : { // ZarzadStore::proxy::listeners
			exception: function(proxy, response, operation,eOpts){
				var def = this;
				/*
				 * response.responseText - zawiera całą treść zwróconą przez serwer
				 */
				console.log('ZarzadStore::proxy::listeners::expetion');
				console.log(proxy);
				console.log(response.responseText);
				console.log(operation);
				console.log(eOpts);

				var resp = Ext.decode(response.responseText);
				switch(operation.action){
					case 'create':
					case 'update':
						switch(resp.success){
							case false:
								switch(resp.ret){
									case ERR_EDB_NOTUNIQUE:
										Ext.Msg.alert('Błąd !','<hr>Nie udało się dodać pracownika. <hr> Prawdopodobny powód : <br> podano symbol lub nazwę zapisane już w bazie, <hr>Proszę nadać unikalne wartości');
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
										Ext.Msg.alert('Błąd !','Nie udało się usunąć pracownika z powodu zalenych od niej rekordów w bazie');
										break;
									}
								def.rejectChanges();
								break;
						}
						break;
				}

			}
		},// ZarzadStore::proxy::listeners

		writer : { // ZarzadStore::proxy::writer
			writeAllFields : false,
			allowSingle : false,
			root : 'data',
			getRecordData : function(record,operation){
				return record.data;
			}
		}, // ZarzadStore::proxy::writer

		reader : { // ZarzadStore::proxy::reader
			type : 'json',
			root : 'data',
			total : 'countTotal'
		} // ZarzadStore::proxy::reader

	}, // ZarzadStore::proxy

	setFirmaId : function(firma_id){
		var def = this;

		def.firma_id = firma_id;
		def.clearFilter();
		def.filter({
			property: 'firma_id',
			value : firma_id,
			operator : '='
		});

		if(firma_id > 0){
			def.load();
		}else{
		}

	},
	warunekZapisu : function(rec){
		if(rec.nazwisko === "" && rec.pesel === '' ){
			return false;
		}
		return true;
	}
});

//var ZarzadStore = Ext.create('ZarzadStore');