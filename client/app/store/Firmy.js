/**
 * @work 4.2.0
 */
Ext.define('CRM.store.Firmy',{
	extend : 'Ext.data.Store',
	model : 'CRM.model.Firmy',
	autoLoad : true,
	autoSync : true,
	autoSave : false,
	idProperty : 'id',

	constructor : function(){
		this.superclass.constructor.call(this,arguments);
		this.proxy.def = this;
		console.log('FirmyStore::constructor');
	},

	listeners : {
		write : function(store,operation,eOpts){
			var record,
					records,
					recIndex,
					recStore;
			console.log('FirmyStore::listenets:write()');
			console.log(operation);
			switch(operation.action){
				case 'create':
					records = operation.getRecords();
					console.log(records);
					for( record in records ){
						recIndex = store.find('tmpId',records[record].data.tmpId);
						recStore = store.getAt(recIndex);
						recStore.data.id = records[record].data.id;
						delete recStore.data.tmpId;
					}
					break;
				case 'update':
					records = operation.getRecords();
					console.log(records);
					for( record in records ){
					}
					break;
			}
		},
		beforesync: function(options,eOpts){
			console.log('FirmyStore::beforesync | start');
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
	},


	proxy:{
		type: 'ajax',
		method : 'POST',
		api : {
			create: '../server/ajax/firmy.php?action=create',
			read: '../server/ajax/firmy.php?action=read',
			update: '../server/ajax/firmy.php?action=update',
			destroy: '../server/ajax/firmy.php?action=delete'
		},

		listeners : {
			exception: function(proxy, response, operation,eOpts){
				var resp = Ext.decode(response.responseText),
						def = proxy.def,
						key;

				switch(operation.action){
					case 'create':
					case 'update':
						if(resp.code === E.code.OK){
							for( key in resp.err ){
								switch(resp.err[key].code){
									case E.code.EDB_NOTUNIQUE:
										Ext.Msg.alert('Błąd !','<hr>Nie udało się dodać firmy. <hr> Prawdopodobny powód : <br> podano symbol lub nazwę zapisane już w bazie, <hr>Proszę nadać unikalne wartości');
										break;
								}
							}
						}else{
							Ext.Msg.alert("Błąd !", resp.msg);
						}
					def.rejectChanges();
					break;

					case 'destroy':
						if(resp.code === E.code.OK){
							for( key in resp.err){
								switch(resp.err[key].code){
									case E.code.EDB_FOREIGNKEY:
										Ext.Msg.alert('Błąd !',E.msg.EDB_FOREIGNKEY);
										break;
									default:
										Ext.Msg.alert('Błąd !',E.msg.UNKNOWN);
								}
							}
						}else{
							Ext.Msg.alert('Błąd !',resp.msg);
						}
						def.rejectChanges();
						break;
				}

			}
		},

		writer : {
			writeAllFields : false,
			allowSingle : false,
			root : 'data'
		},

		reader : {
			type : 'json',
			root : 'data',
			total : 'countTotal'
		}

	}


});