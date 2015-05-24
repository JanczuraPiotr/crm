/**
 * @confirm 2014-12-29 extjs 5.1.0
 */
Ext.define('FirmyOddzialyStore',{
	extend : 'Ext.data.Store',
	model : FirmyOddzialyModel,
	autoLoad : false,
	autoSync : true,
	autoSave : false,
	remoteFilter : true,
	idProperty : 'id',
	firma_id : 0,

	constructor : function(){
		this.firma_id = 0;
		this.callParent(arguments);
		this.proxy.def = this;
	},

	listeners : { //FirmyOddzialyStore::listenets
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
			if(this.firma_id === 0){
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
	},//FirmyOddzialyStore::liste


	proxy : { // FirmyOddzialyStore::proxy
		type: 'ajax',
		method : 'POST',
		api : {
			create: '../server/ajax/firmy-oddzialy.php?action=create',
			read: '../server/ajax/firmy-oddzialy.php?action=read',
			update: '../server/ajax/firmy-oddzialy.php?action=update',
			destroy: '../server/ajax/firmy-oddzialy.php?action=delete'
		},

		listeners : { // FirmyOddzialyStore::proxy::listeners
			exception: function(proxy, response, operation,eOpts){
				var def = proxy.def;
				var resp = Ext.decode(response.responseText);
				var key;
				switch(operation.action){

					case 'create':
					case 'update':
						if(resp.code === E.code.OK){
							for(key in resp.err){
								switch(resp.err[key].code){
									case E.code.EDB_NOTUNIQUE:
										Ext.Msg.alert('Błąd !','<hr>Nie udało się dodać oddziału firmy. <hr> Prawdopodobny powód : <br> podano symbol lub nazwę zapisane już w bazie, <hr>Proszę nadać unikalne wartości');
										break;
									default:
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
						if(resp.code !== E.code.OK){
							// Cała operacje nie powidodła się
							Ext.Msg.alert("Błąd !", resp.msg);
						}else{
							// Jako całość operacja powiodła się ale należy przetestować powodzenie odnośnie pojedynczych rekordów
							for(key in  resp.err){
								switch(resp.err[key].code){
									case E.code.EDB_FOREIGNKEY:
										Ext.Msg.alert('Błąd !','Nie udało się usunąć oddzialu firmy z powodu zalenych od niej rekordów w bazie');
										break;
								}

							}
						}
						def.rejectChanges();
						break;
				}

			}
		},// FirmyOddzialyStore::proxy::listeners

		writer : { // FirmyOddzialyStore::proxy::writer
			writeAllFields : false,
			allowSingle : false,
			rootProperty : 'data',
			getRecordData : function(record,operation){
				return record.data;
			}
		}, // FirmyOddzialyStore::proxy::writer

		reader : { // FirmyOddzialyStore::proxy::reader
			type : 'json',
			rootProperty : 'data',
			totalProperty : 'countTotal'
		} // FirmyOddzialyStore::proxy::reader

	}, // FirmyOddzialyStore::proxy
	setFirmaId : function(firma_id){
		var def = this;
		def.firma_id = firma_id;
		def.clearFilter(true);
		def.filter({
			property : 'firma_id',
			value    : firma_id,
			operator : '='
		});

		if(firma_id > 0){
			def.load();
		}else{
		}

	}

});

//var FirmyOddzialyStore = Ext.create('FirmyOddzialyStore');