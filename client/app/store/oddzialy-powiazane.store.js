/**
 * @task 2014-10-30 Zamiana response.ret >>> response.code
 * @task 2014-10-30 Dodanie do response tablicy "err" informącej o błędach rozpoznanych indywidualnie dla każdej encji podczas przetwarzania przez BusinessLogic
 * @task 4.2.0
 */
Ext.define('OddzialyPowiazaneStore',{
	extend : 'Ext.data.Store',
	model : BankiOddzialyModel,
	autoLoad : false,
	autoSync : true,
	autoSave : false,
	idProperty : 'id',

	constructor : function(){
		var def = this;
		def.firma_oddzial_id = -1;
		def.superclass.constructor.call(this,arguments);
		def.setFirmaOddzialId(def.firma_oddzial_id);
	},

	proxy:{ // OddzialyPowiazaneStore::proxy
		type: 'ajax',
		method : 'POST',
		api : {
			//create: '../server/ajax/oddzialy-powiazane.php?action=create',
			read: '../server/ajax/oddzialy-powiazane.php?action=read'
			//update: '../server/ajax/oddzialy-powiazane.php?action=update',
			//destroy: '../server/ajax/oddzialy-powiazane.php?action=delete'
		},
		reader : {
			type : 'json',
			rootProperty : 'data',
			totalProperty : 'countTotal'
		}
	},

	setFirmaOddzialId : function(firma_oddzial_id){
		var def = this;
		if(def.firma_oddzial_id != firma_oddzial_id){
			def.firma_oddzial_id = firma_oddzial_id;
			def.clearFiler();
			def.filter({
				property: 'firma_oddzial_id',
				value: def.firma_oddzial_id,
				operator: '='
			});
			def.load();
		}

	}

});
