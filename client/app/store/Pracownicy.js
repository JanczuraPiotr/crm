/**
 * @task 2014-10-30 Dodanie do response tablicy "err" informującej o błędach rozpoznanych indywidualnie dla każdej encji podczas przetwarzania przez BusinessLogic
 * @work 4.2.0
 */
Ext.define('CRM.store.Pracownicy',{
	extend : 'Ext.data.Store',
	model : 'CRM.model.Pracownicy',
	autoLoad : false,
	autoSync : true,
	autoSave : false,
	idProperty : 'id',

	requires : [
		'CRM.model.Pracownicy'
	],

	constructor : function(){
		var def = this; // @todo usun zmienną globalną thisPS = this >> var def = this
		def.firmaId = 0;
		def.callParent(arguments);
	},

	listeners : { //PracownicyStore::listenets
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
			var thisOPS = this;
			var data = null;
			if(thisOPS.firma_id === 0){
				return false;
			}
			for(var action in options){
				switch(action){
					case 'create':
						if(thisOPS.warunekZapisu(options.create[0].data) === false){
							return false;
						}
						break;
					case 'update':
						if(options.update !== undefined){
							data = options.update[0].data;
						}
						if(data.tmpId !== undefined || thisOPS.warunekZapisu(data) === false){
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
	},//PracownicyStore::liste


	proxy:{ // PracownicyStore::proxy
		type: 'ajax',
		method : 'POST',
		api : {
			create: '../server/ajax/pracownicy.php?action=create',
			read: '../server/ajax/pracownicy.php?action=read',
			update: '../server/ajax/pracownicy.php?action=update',
			destroy: '../server/ajax/pracownicy.php?action=delete'
		},

		listeners : { // PracownicyStore::proxy::listeners
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
										Ext.Msg.alert('Błąd !','<hr>Nie udało się dodać pracownika. <hr> Prawdopodobny powód : <br> podano symbol lub nazwę zapisane już w bazie, <hr>Proszę nadać unikalne wartości');
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
										Ext.Msg.alert('Błąd !','Nie udało się usunąć pracownika z powodu zalenych od niej rekordów w bazie');
										break;
									}
								def.rejectChanges();
								break;
						}
						break;
				}

			}
		},// PracownicyStore::proxy::listeners

		writer : { // PracownicyStore::proxy::writer
			writeAllFields : false,
			allowSingle : false,
			root : 'data',
			getRecordData : function(record,operation){
				return record.data;
			}
		}, // PracownicyStore::proxy::writer

		reader : { // PracownicyStore::proxy::reader
			type : 'json',
			root : 'data',
			total : 'countTotal'
		} // PracownicyStore::proxy::reader

	}, // PracownicyStore::proxy

	setFirmaId : function(firma_id){
		var def = this;

		def.firmaId = firma_id;
		def.clearFilter();
		def.filter({
			property: 'firma_id',
			value: firma_id,
			operator: '='
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

//var PracownicyStore = Ext.create('PracownicyStore');