/**
 * @work 2014-10-30 Zamiana response.ret >>> response.code
 * @work 2014-10-30 Dodanie do response tablicy "err" informącej o błędach rozpoznanych indywidualnie dla każdej encji podczas przetwarzania przez BusinessLogic
 * @work 2014-09-24 extjs 4.2.2 >> extjs 5.0.1
 * @confirm 2014-09-04 Zmiana znaczenia dat i sposobu notowania wykonywanych kroków.
 */
Ext.define('GeneratorZadanStore',{
	extend : 'Ext.data.Store',
	model : GeneratorZadanModel,
	autoLoad : false,
	autoSync : true,
	autoSave : false,
	idProperty : 'id',

	constructor : function(){
		thisGeneratorZadanStore = this; // @todo usunąć zmienną globalną thisGeneratorZadanStore >> var def = this
		thisGeneratorZadanStore.bank_id = 0;
		thisGeneratorZadanStore.superclass.constructor.call(this,arguments);
	},

	listeners : { //GeneratorZadanStore::listenets
		write : function(store,operation,eOpts){
			var def = this;
			switch(operation.action){
				case 'create':
					for(var record in operation.records){
						var recIndex = store.find('tmpId',operation.records[record].data.tmpId);
						var recStore = store.getAt(recIndex);
						recStore.data.id = operation.records[record].data.id;
						delete recStore.data.tmpId;
					}
					def.onWriteCreate(store,operation,eOpts);
					break;
			}
		},
		beforesync: function(options,eOpts){
			if(thisGeneratorZadanStore.bank_id === 0){
				return false;
			}
			for(var action in options){
				switch(action){
					case 'create':
						var	data = options.create[0].data;
						if(data.symbol === '' || data.nazwa === ''){
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
	},//GeneratorZadanStore::liste


	proxy:{ // GeneratorZadanStore::proxy
		type: 'ajax',
		method : 'POST',
		api : {
			create: '../server/ajax/generator-zadan.php?action=create',
			read: '../server/ajax/generator-zadan.php?action=read',
			update: '../server/ajax/generator-zadan.php?action=update',
			destroy: '../server/ajax/generator-zadan.php?action=delete'
		},

		listeners : { // GeneratorZadanStore::proxy::listeners
			exception: function(proxy, response, operation,eOpts){
				var resp = Ext.decode(response.responseText);

				switch(operation.action){
					case 'create':
					case 'update':
						switch(resp.success){
							case false:
								switch(resp.ret){
									case ERR_EDB_NOTUNIQUE:
										Ext.Msg.alert('Błąd !','<hr>Nie udało się dodać banku. <hr> Prawdopodobny powód : <br> podano symbol lub nazwę zapisane już w bazie, <hr>Proszę nadać unikalne wartości');
										break;
								}
								thisGeneratorZadanStore.rejectChanges();
								break;
						}

					case 'destroy':
						switch(resp.success){
							case false:
								switch(resp.ret){
									case ERR_EDB_FOREIGNKEY:
										Ext.Msg.alert('Błąd !','Nie udało się usunąć banku z powodu zalenych od niej rekordów w bazie');
										break;
								}
								thisGeneratorZadanStore.rejectChanges();
								break;
						}
						break;
				}

			}
		},// GeneratorZadanStore::proxy::listeners

		writer : { // GeneratorZadanStore::proxy::writer
			writeAllFields : false,
			allowSingle : false,
			rootProperty : 'data',
			getRecordData : function(record,operation){
				return record.data;
			}
		}, // GeneratorZadanStore::proxy::writer

		reader : { // GeneratorZadanStore::proxy::reader
			type : 'json',
			rootPropetyy : 'data',
			totalProperty : 'countTotal'
		} // CGeneratorZadanStore::proxy::reader

	}, // GeneratorZadanStore::proxy
	onWriteCreate : function(store,operation,eOpts){
		console.log('GeneratorZadanStore::onWriteCreate');
	},
	setBankId : function(bank_id){
		var def = this;
		def.bank_id = bank_id;
		if(bank_id > 0){
			thisGeneratorZadanStore.clearFilter();
			thisGeneratorZadanStore.filter({
				property : 'bank_id',
				value : bank_id,
				operator : '='
			});
			thisGeneratorZadanStore.load();
		}else{
			thisGeneratorZadanStore.clearFilter();
			thisGeneratorZadanStore.filter({
				property : 'bank_id',
				value : bank_id,
				operator : '='
			});
		}
	}

});

//var GeneratorZadanStore = new Ext.create('GeneratorZadanStore');