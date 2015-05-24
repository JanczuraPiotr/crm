/**
 * Odczyt informacji o stanowiskach pracy
 * @prace 2014-10-30 Dodanie do response tablicy "err" informącej o błędach rozpoznanych indywidualnie dla każdej encji podczas przetwarzania przez BusinessLogic
 * @confirm 2015-03-12 extjs 5.1.0
 * @confirm 2014-08-29
 * @err 2014-09-22 Nie działa tworzenie stanowisk pracy ze względu na błędy kluczy obcych
 * @todo Wybór elementu w połączonym gridzie "oddziały firm" powoduje dwukrotny odczyt z servera
 */
Ext.define('StanowiskaStore',{
	extend : 'Ext.data.Store',
	model : StanowiskaModel,
	autoLoad : false,
	autoSync : true,
	autoSave : false,
	remoteFilter : true,
	idProperty : 'id',

	constructor : function(){
		this.placowka_id = 0;
		this.superclass.constructor.call(this,arguments);
	},

	setPlacowkaId : function(placowka_id){
		var def = this;

		if(def.placowka_id !== placowka_id){
			def.placowka_id = placowka_id;
			if(def.placowka_id > 0){
				def.clearFilter(true);
				def.filter({
					property : 'placowka_id',
					value    : placowka_id,
					operator : '='
				});

			}else{
				def.clearFilter();

			}
		}
	},
	warunekZapisu : function(rec){
		if(rec.nazwa === "" && rec.symbol === '' ){
			return false;
		}
		return true;
	},
	listeners : { // this.listeners
		write : function(store,operation,eOpts){ // this.listeners.write
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
		}, // this.listeners.write
		beforesync: function(options,eOpts){ // this.listeners.beforesync
			if(this.placowka_id === 0){
				return false;
			}
			for(var action in options){
				switch(action){
					case 'create':
						if(this.warunekZapisu(options.create[0].data) === false){
							return false;
						}
						break;
					case 'update':
						var data = null;
						if(options.update !== undefined){
							data = options.update[0].data;
						}
						if(data.tmpId !== undefined || this.warunekZapisu(data) === false){
							// Tworzony jest nowy rekord
							return false;
						}
						if(data.id === 0){
							return false;
						}
						break;
				}
			}
		} // this.listeners.beforesync
	},// this.listeners
	proxy : { // this.proxy
		type: 'ajax',
		method : 'POST',
		api : {
			create: '../server/ajax/stanowiska.php?action=create',
			read: '../server/ajax/stanowiska.php?action=read',
			update: '../server/ajax/stanowiska.php?action=update',
			destroy: '../server/ajax/stanowiska.php?action=delete'
		},
		listeners : { // this.proxy.listeners
			exception: function(proxy, response, operation,eOpts){ // this.proxy.listeners.exception
				var resp = Ext.decode(response.responseText);
				switch(operation.action){
					case 'create':
					case 'update':
						switch(resp.success){
							case false:
								switch(resp.ret){
									case ERR_EDB_NOTUNIQUE:
										Ext.Msg.alert('Błąd !',resp.msg);
										//Ext.Msg.alert('Błąd !','<hr>Nie udało się dodać stanowiska. <hr> Prawdopodobny powód : <br> podano symbol lub nazwę zapisane już w bazie, <hr>Proszę nadać unikalne wartości');
										break;
								}
								this.rejectChanges();
								break;
						}

					case 'destroy':
						switch(resp.success){
							case false:
								switch(resp.ret){
									case ERR_EDB_FOREIGNKEY:
										Ext.Msg.alert('Błąd !','Nie udało się usunąć stanowiska z powodu zalenych od niej rekordów w bazie');
										break;
									}
								this.rejectChanges();
								break;
						}
						break;
				}

			} // this.proxy.listeners.exception
		},// this.proxy.listeners

		writer : { // this.proxy.writer
			writeAllFields : false,
			allowSingle : false,
			rootProperty : 'data',
			getRecordData : function(record,operation){
				return record.data;
			}
		}, // this.proxy.writer

		reader : { // this.proxy.reader
			type : 'json',
//				url : '../server/ajax/stanowiska.php?action=read',
			rootProperty : 'data',
			totalProperty : 'countTotal'
		} // this.proxy.reader

	} // this.proxy

});

//var StanowiskaStore = Ext.create('StanowiskaStore');