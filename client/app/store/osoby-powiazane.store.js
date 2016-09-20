/**
 * @task 4.2.0
 * @task 2014-10-30 Dodanie do response tablicy "err" informącej o błędach rozpoznanych indywidualnie dla każdej encji podczas przetwarzania przez BusinessLogic
 */
Ext.define('OsobyPowiazaneStore',{
	extend : 'Ext.data.Store',
	xtype : 'osoby-powiazane-store',
	alias : 'osoby-powiazane-store',
	model : OsobyPowiazaneModel,
	autoLoad : false,
	autoSync : true,
	autoSave : true,
	remoteFilter : true,
	idProperty : 'id',

	constructor : function(){
		var def = this;
		def.klient_id = 0;
		def.callParent(arguments);
	},

	listeners : { //OsobyPowiazaneStore::listenets
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
					break;
			}
		},
		beforesync: function(options,eOpts){
			var def = this;
			var data = null;
			if(def.klient_id === 0){
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
						break;
				}
			}
		}
	},


	proxy:{
		type: 'ajax',
		method : 'POST',
		api : {
			create: '../server/ajax/osoby-powiazane.php?action=create',
			read: '../server/ajax/osoby-powiazane.php?action=read',
			update: '../server/ajax/osoby-powiazane.php?action=update',
			destroy: '../server/ajax/osoby-powiazane.php?action=delete'
		},

		listeners : { // OsobyPowiazaneStore::proxy::listeners
			exception: function(proxy, response, operation,eOpts){
				var def = this;
				var resp = Ext.decode(response.responseText);
				var key;
				switch(operation.action){
					case 'create':
					case 'update': // @done 2014-11-03 Nowa struktura jsona
						if(resp.code === E.code.OK){
							// Nie powiodła się operacja na pojedynczych zestawach danych.
							for(key in resp.err){
								switch(resp.err[key].code){
									case E.code.EDB_NOTUNIQUE:
										Ext.Msg.alert('Błąd !','<hr>Nie udało się dodać oddziału banku. <hr> Prawdopodobny powód : <br> podano symbol lub nazwę zapisane już w bazie, <hr>Proszę nadać unikalne wartości');
										break;
									default :
										Ext.Msg.alert('Błąd !', 'Nie znany ...');
								}
							}
						}else{
							// Cała operacje nie powidodła się
							Ext.Msg.alert("Błąd !", resp.msg);
						}
						def.rejectChanges();
						break;

					case 'destroy': // @done 2014-11-03 Nowa struktura jsona
						if(resp.code === E.code.OK){
							// Nie powiodła się operacja na pojedynczych zestawach danych.
							for(key in resp.err){
								switch(resp.err[key].code){
									case E.code.EDB_FOREIGNKEY:
										Ext.Msg.alert('Błąd !', E.msg.EDB_FOREIGNKEY);
										break;
									default :
										Ext.Msg.alert('Błąd !', E.msg.UNKNOWN);
								}
							}
						}else{
							// Cała operacje nie powidodła się
							Ext.Msg.alert("Błąd !", resp.msg);
						}
						def.rejectChanges();
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

	setKlientId : function(klient_id){
		var def = this;
		var filter = [];

		if( def.klient_id !== klient_id){
			def.klient_id = klient_id;
			if(def.klient_id !== 0 ){
				def.clearFilter(true);
				filter.push({
					'property' : 'klient_id',
					'operator' : '=',
					'value' : klient_id
				});
				def.filter(filter);
			}else{
				def.clearFilter();
			}
		}

	},
	warunekZapisu : function(rec){
		if(rec.nazwa === "" && ( rec.nip === '' || rec.pesel === '') ){
			return false;
		}
		return true;
	}
});

//var OsobyPowiazaneStore = Ext.create('OsobyPowiazaneStore');