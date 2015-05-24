/**
 * @prace 2014-10-30 Zamiana response.ret >>> response.code
 * @prace 2014-10-30 Dodanie do response tablicy "err" informącej o błędach rozpoznanych indywidualnie dla każdej encji podczas przetwarzania przez BusinessLogic
 * @prace 2014-09-24 extjs 4.2.2 >> extjs 5.0.1
 */
Ext.define('OddzialyNiepowiazaneStore',{
	extend : 'Ext.data.Store',
	model : BankiOddzialyModel,
	autoLoad : false,
	autoSync : true,
	autoSave : false,
	idProperty : 'id',
	remoteFilter : true,

	constructor : function(){
		var def = this;
		def.bank_id = -1;
		def.firma_oddzial_id = -1;
		def.callParent(arguments);
		def.setFirmaOddzialId(def.firma_oddzial_id);
	},

	proxy:{
		type: 'ajax',
		method : 'POST',
		api : {
			//create: '../server/ajax/oddzialy-niepowiazane.php?action=create',
			read: '../server/ajax/oddzialy-niepowiazane.php?action=read'
			//update: '../server/ajax/oddzialy-niepowiazane.php?action=update',
			//destroy: '../server/ajax/oddzialy-niepowiazane.php?action=delete'
		},
		reader : {
			type : 'json',
			rootProperty : 'data',
			totalProperty : 'countTotal'
		}
	},

	setBankId : function(bank_id){
		var def = this;
		var filter = [];
		if(def.bank_id !== bank_id){
			def.bank_id = bank_id;
			def.clearFilter();
			filter.push({
				property : 'bank_id',
				value : def.bank_id,
				operator : '='
			});
			filter.push({
				property: 'firma_oddzial_id',
				value: def.firma_oddzial_id,
				operator: '='
			});
			def.filter(filter);
			if(def.bank_id > 0 && def.firma_oddzial_id > 0){
				def.load();
			}
		}
	},

	setFirmaOddzialId : function(firma_oddzial_id){
		var def = this;
		var filter = [];
		if(def.firma_oddzial_id !== firma_oddzial_id){
			def.firma_oddzial_id = firma_oddzial_id;
			def.clearFilter();
			filter.push({
				property : 'bank_id',
				value : def.bank_id,
				operator : '='
			});
			filter.push({
				property: 'firma_oddzial_id',
				value: def.firma_oddzial_id,
				operator: '='
			});
			def.filter(filter);
			if(def.bank_id > 0 && def.firma_oddzial_id > 0){
				def.load();
			}
		}
	}

});
