/**
 * @task 2014-10-30 Zamiana response.ret >>> response.code
 * @task 2014-10-30 Dodanie do response tablicy "err" informącej o błędach rozpoznanych indywidualnie dla każdej encji podczas przetwarzania przez BusinessLogic
 * @task 4.2.0
 */
Ext.define('WiazanieFirmBankowStore',{
	extend : 'Ext.data.Store',
	alias : 'wiazanie-firm-bankow-store',
	model : WiazanieFirmBankowModel,
	autoLoad : false,
	autoSync : false,
	autoSave : false,
	remoteFilter : true,
	idProperty : 'id',

	constructor : function(){
		var def = this;

		def.firma_id = -1;
		def.bank_id = -1;

		// Wiążemy ze sobą te dwa indexy
		def.firma_oddzial_id = -1;
		def.bank_oddzial_id = -1;

		def.callParent(arguments);
	},

	listeners : { //WiazanieFirmBankowStore::listenets
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
					// Utowrzenie rekordu odbyło się na podstawie innej tabeli.
					// Konieczne jest pobranie jego treści z bazy danych
					store.load();
					break;
			}
		},
		beforesync: function(options,eOpts){
			for(var action in options){
				switch(action){
					case 'update':
						if( !def.warunekUpdate(def.firma_id,def.firma_oddzial_id,def.stanowisko_id,def.data_od)){
							return false;
						}
						options.update[0].data.tmpId = Ext.id();
						options.update[0].data.stanowisko_id = def.stanowisko_id;
						break
				}
			}
		}
	},//WiazanieFirmBankowStore::liste
	proxy:{ // WiazanieFirmBankowStore::proxy
		type: 'ajax',
		method : 'POST',
		api : {
			create: '../server/ajax/wiazanie-firm-bankow.php?action=create', // Zatrudnienie
			read: '../server/ajax/wiazanie-firm-bankow.php?action=read',
			update: '../server/ajax/wiazanie-firm-bankow.php?action=zatrudnij', // Zwolnienie
			destroy : '../server/ajax/wiazanie-firm-bankow.php?action=delete'
		},

		listeners : { // WiazanieFirmBankowStore::proxy::listeners
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
		},// WiazanieFirmBankowStore::proxy::listeners

		writer : { // WiazanieFirmBankowStore::proxy::writer
			writeAllFields : false,
			allowSingle : false,
			rootProperty : 'data',
			getRecordData : function(record,operation){
				return record.data;
			}
		}, // WiazanieFirmBankowStore::proxy::writer

		reader : { // WiazanieFirmBankowStore::proxy::reader
			type : 'json',
			rootProperty : 'data',
			totalProperty : 'countTotal'
		} // CWiazanieFirmBankowStore::proxy::reader

	}, // WiazanieFirmBankowStore::proxy


	setFirmaId : function(firma_id){
		this.firma_id = firma_id;
	},
	setFirmaOddzialId : function(firma_oddzial_id){
		var def = this;
		def.firma_oddzial_id = firma_oddzial_id;
		def.clearFilter();
		def.filter({
			property: 'firma_oddzial_id',
			value: def.firma_oddzial_id,
			operator: '='
		});
		if(def.firma_oddzial_id > 0){
			def.load();
		}else{
		}
	},
	setFirmaOddzial : function(firma_id,firma_oddzial_id){
		var def = this;
		def.setFirmaId(firma_id);
		def.setFirmaOddzialId(firma_oddzial_id);
	},
	warunekUpdate : function(firma_id,placowka_id,stanowisko_id,data_od){
		if(firma_id < 0){
			return false;
		}
		if(placowka_id < 0 ){
			return false;
		}
		if(stanowisko_id < 0){
			return false;
		}
		if(data_od === null){
			return false;
		}
		return true;
	}
});
