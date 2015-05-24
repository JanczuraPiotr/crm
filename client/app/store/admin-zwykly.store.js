/**
 * @prace 2014-10-30 Dodanie do response tablicy "err" informącej o błędach rozpoznanych indywidualnie dla każdej encji podczas przetwarzania przez BusinessLogic
 * @prace 2014-09-24 extjs 4.2.2 >> extjs 5.0.1
 */
Ext.define('AdminZwyklyStore',{
	extend : 'Ext.data.Store',
	alias : 'admin-zwykly-store',
  model: 'AdminZwyklyModel',
	pageSize : 15,
	autoLoad : true,
	autoSync : true,
	autoSave : false,
	idProperty : 'id',

	constructor : function(){
		thisAZS = this; // @todo usuń zmienną globalną thisASZ = this >> var def = this
		thisAZS.superclass.constructor.call(thisAZS, arguments);
	},

	listeners : { //AdminZwyklyStore::listenets
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
			var data = null;
			for(var action in options){
				switch(action){
					case 'update':
						if(options.update !== undefined){
							data = options.update[0].data;
						}else if(options.create !== undefined){
							data = options.create[0].data;
						}
						if(data.tmpId !== undefined && data.login !== ''){
							// Tworzony jest nowy rekord
							return true;
						}
						if(data.id === 0){
							return false;
						}
						break
				}
			}
		}
	},//AdminZwyklyStore::listenets

	proxy:{ // AdminZwyklyStore::proxy
		type: 'ajax',
		method : 'POST',
		api : {
			create: '../server/ajax/administratorzy.php?action=create',
			read: '../server/ajax/administratorzy.php?action=read',
			update: '../server/ajax/administratorzy.php?action=update',
			destroy: '../server/ajax/administratorzy.php?action=delete',
			validate: 'api/validate.php' // validation method
		},

		listeners : { // AdminZwyklyStore::proxy::listeners
			exception: function(proxy, response, operation){
				var resp = Ext.decode(response.responseText);
				var records = null;
				switch(operation.action){
					case 'destroy':
						records = operation.getRecords();
						switch(resp.success){
							case false:
								for( var record in records){
									Ext.Msg.alert('Nie udało się usunąć administratora','login :: '+records[record].getData().login);
								}
								thisAZS.rejectChanges();
								break;
						}
						break;
				}

			}
		},// AdminZwyklyStore::proxy::listeners

		writer : { // CAdminZwyklyStore::proxy::writer
			writeAllFields : false,
			allowSingle : false,
			rootProperty : 'data',
			getRecordData : function(record,operation){
				// Utworzenie metody jest konieczne by na serwer były wysyłane składowe rekordu
				// nie opisane modelem a które dodano do niego jako parametry dodatkowe.
				return record.data;
			}
		}, // CAdminZwyklyStore::proxy::writer

		reader : { // CAdminZwyklyStore::proxy::reader
			type : 'json',
			rootProperty : 'data',
			totalProperty : 'countTotal'
		} // CAdminZwyklyStore::proxy::reader

	} // AdminZwyklyStore::proxy

 });

//var AdminZwyklyStore = Ext.create('AdminZwyklyStore');