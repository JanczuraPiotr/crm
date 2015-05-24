/**
 * @confirm 2015-01-01 ExtJS 5.1.0
 * @confirm 2014-12-30 Dodanie do response tablicy "err" informącej o błędach rozpoznanych indywidualnie dla każdej encji podczas przetwarzania przez BusinessLogic
 * @todo Rozbić komunikację by kolumna "opis" była wczytywana dopiero bo zaznaczeniu klienta
 */

Ext.define('KlienciStore',{
	extend : 'Ext.data.Store',
	model : KlienciModel,
	autoLoad : true,
	autoSync : true,
	autoSave : true,
	remoteFilter : true,
	idProperty : 'id',
	pageSize : 20,

	constructor : function(){
		this.callParent(arguments);
		this.proxy.def = this;
	},
	listeners : {
		write : function(store,operation,eOpts){
			var record,
					records,
					recIndex,
					recStore;
			switch(operation.getRequest().getAction()){
				case 'create':
					records = operation.getRecords();
					for( record in records ){
						recIndex = store.find('tmpId',records[record].data.tmpId);
						recStore = store.getAt(recIndex);
						recStore.set('id', records[record].data.id);
						delete recStore.data.tmpId;
						recStore.set('data_kont', records[record].data.data_kont);
						recStore.set('data_od',	records[record].data.data_od);
					}
					break;
			}
		},
		beforesync: function(options,eOpts){
			var def = this;
			var data;
			for(var action in options){
				switch(action){
					case 'create':
						if( def.warunekZapisu(options.create[0].data) === false ){
							return false;
						}
						break;
					case 'update':
						data = options.update[0].data;
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
	},
	proxy:{
		type: 'ajax',
		method : 'POST',
		api : {
			create: '../server/ajax/klienci.php?action=create',
			read: '../server/ajax/klienci.php?action=read',
			update: '../server/ajax/klienci.php?action=update',
			destroy: '../server/ajax/klienci.php?action=delete'
		},

		listeners : {
			exception: function(proxy, response, operation,eOpts){
				var def = proxy.def;
				var resp = Ext.decode(response.responseText);
				var key;
				switch(operation.action){
					case 'create':
					case 'update':
						if(resp.code === E.code.OK){
							// Nie powiodła się operacja na pojedynczych zestawach danych.
							for(key in resp.err){
								switch(resp.err[key].code){
									case E.code.EDB_NOTUNIQUE:
										Ext.Msg.alert('Błąd !','<hr>Nie udało się dodać klienta. <hr> Prawdopodobny powód : <br> klient o podanym peselu lub nipie istnieje już w bazie,');
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

					case 'destroy':
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
	warunekZapisu : function(rec){
		if(rec.nazwa === "" && ( rec.nip === '' || rec.pesel === '') ){
			return false;
		}
		return true;
	}
});