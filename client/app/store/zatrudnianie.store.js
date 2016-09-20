/**
 * Generowane zdarzenia:
 *
 *		ZatrudnianieStore::przypisano-do-stanowiska-pracy - Powiodła się próba przypisania pracownika do pustego stanowiska pracy
 *			parametry :
 *				record  - rekord opisujący operację przypisania
 *
 *		ZatrudnianieStore::blad-przypisania-do-stanowiska-pracy
 *			parametry :
 *				record  - rekord opisujący operację przypisania
 *
 *
 * @task 4.2.0
 * @task 2014-10-30 Zamiana response.ret >>> response.code
 * @task 2014-10-30 Dodanie do response tablicy "err" informującej o błędach rozpoznanych indywidualnie dla każdej encji podczas przetwarzania przez BusinessLogic
 */

Ext.define('ZatrudnianieStore',{
	extend : 'Ext.data.Store',
	model : ZatrudnianieModel,
	autoLoad : true,
	autoSync : true,
	autoSave : false,
	remoteFilter : true,
	idProperty : 'id',

	constructor : function(){
		console.log('ZatrudnianieStore::constructor()');
		var def = this;
		def.zatrudnienie_id = -1;
		def.firma_id = -1;
		def.placowka_id = -1;
		def.stanowisko_id = -1;
		def.data_od = null;

		def.callParent(arguments);

		def.proxy.def = this;
	},

	listeners : { //ZatrudnianieStore::listenets
		write : function(store,operation,eOpts){
			var def = this,
					response,
					record,   // Record nadesłany z servera jako odpowiedź na próbę przypisania pracownika do stanowiska
					recIndex, // index recordu w store, który zainicjował operację przypisania pracownika do stanowiska
					recStore; // record w store, który zainicjował operację przypisania - który należy zmodyfikować odpowiedzią
									  // nadesłaną z servera
			switch(operation.getRequest().getAction()){
				case 'update':
					if(operation.wasSuccessful()){
						response = Ext.decode(operation.getResponse().responseText);
						if(response.err.length === 0){
							record = operation.getRecords()[0];
							def.zatrudnienie_id = record.get('id');
							recIndex = store.find('tmpId',record.get('tmpId'));
							recStore = store.getAt(recIndex);
							recStore.set('id',record.get('id'));
							recStore.commit();
							def.fireEvent('przypisanodostanowiska',recStore);
						}else{
							def.fireEvent('bladprzypisaniadostanowiska',recStore);
						}
					}else{
						def.fireEvent('bladprzypisaniadostanowiska',recStore);
					}
					break;
			}
		},
		beforesync: function(options,eOpts){
			var def = this;
			for(var action in options){
				switch(action){
					case 'update':
						if( ! def.warunekUpdate(def.firma_id,def.placowka_id,def.stanowisko_id,def.data_od) ){
							return false;
						}
						options.update[0].data.tmpId = Ext.id();
						options.update[0].data.stanowisko_id = def.stanowisko_id;
						break;
				}
			}
		}
	},//ZatrudnianieStore::liste
	proxy : { // ZatrudnianieStore::proxy
		type: 'ajax',
		method : 'POST',
		api : {
			create: '../server/ajax/zatrudnianie.php?action=create', // Zatrudnienie
			read: '../server/ajax/zatrudnianie.php?action=read',
			update: '../server/ajax/zatrudnianie.php?action=zatrudnij', // Zwolnienie
			destroy : '../server/ajax/zatrudnianie.php?action=destroy'
		},

		listeners : { // ZatrudnianieStore::proxy::listeners
			exception: function(proxy, response, operation,eOpts){
				var def = proxy.def;
				var resp = Ext.decode(response.responseText);

				switch(operation.getRequest().getAction()){
					case 'create':
					case 'update':
						if( ! operation.wasSuccessful() ){
							def.nieZatrudniono(operation.records[0].data.pracownik_id);
							switch(resp.ret){
								case ERR_EDB_NOTUNIQUE:
									Ext.Msg.alert('Błąd !','<hr>Nie udało się dodać dokumentu. <hr> Prawdopodobny powód : <br> podano symbol lub nazwę zapisane już w bazie, <hr>Proszę nadać unikalne wartości');
									break;
							}
							def.rejectChanges();
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
		},// ZatrudnianieStore::proxy::listeners

		writer : { // ZatrudnianieStore::proxy::writer
			writeAllFields : false,
			allowSingle : false,
			rootProperty : 'data',
			getRecordData : function(record,operation){
				return record.data;
			}
		}, // ZatrudnianieStore::proxy::writer
		reader : { // ZatrudnianieStore::proxy::reader
			type : 'json',
			rootProperty : 'data',
			totalProperty : 'countTotal'
		}  // ZatrudnianieStore::proxy::reader

	}, // ZatrudnianieStore::proxy

	setFirmaId : function(firma_id){
		var def = this;
		def.firma_id = firma_id;
	},
	setPlacowkaId : function(placowka_id){
		var def = this;
		def.placowka_id = placowka_id;
	},
	setStanowiskoId : function(stanowisko_id){
		var def = this;
		def.stanowisko_id = stanowisko_id;
	},
	setDataOd : function(data_od){
		var def = this;
		def.data_od = data_od;
	},
	setStanowisko : function(firma_id, placowka_id,stanowisko_id,data_od){
		var def = this;
		def.setFirmaId(firma_id);
		def.setPlacowkaId(placowka_id);
		def.setStanowiskoId(stanowisko_id);
		def.setDataOd(data_od);
		def.clearFilter();
		def.filter({
			'property' : 'firma_id',
			'value' : def.firma_id,
			'operator' : '='
		});
		if(def.placowka_id > 0){
			def.load();
		}else{
		}
	},
	czyZatrudniono : function(){
		var def = this;
		return (def.zatrudniono_id > 0 ? true : false);
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
	},
	onPrzypisanoDoStanowiska : function(record){}
});
