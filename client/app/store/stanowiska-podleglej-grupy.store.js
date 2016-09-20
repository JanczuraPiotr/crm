/**
 * @task 4.2.0
 * @task 2014-10-30 Zamiana response.ret >>> response.code
 * @task 2014-10-30 Dodanie do response tablicy "err" informującej o błędach rozpoznanych indywidualnie dla każdej encji podczas przetwarzania przez BusinessLogic
 */
Ext.define('StanowiskaPodleglejGrupyStore',{
	extend : 'Ext.data.Store',
	model : StanowiskaModel,
	autoLoad : false,
	autoSync : true,
	autoSave : false,
	remoteFilter : true,
	idProperty : 'id',

	proxy:{
		type: 'ajax',
		method : 'POST',
		api : {
			read: '../server/ajax/stanowiska-podleglej-grupy.php?action=read'
		},
		reader : {
			type : 'json',
			rootProperty : 'data',
			totalProperty : 'countTotal'
		}
	},

	setPlacowkaId : function(placowka_id){
		var def = this;

		def.placowka_id = placowka_id;
		def.clearFilter();
		def.filter({
			property : 'placowka_id',
			value    : placowka_id,
			operator : '='
		});

		if(placowka_id > 0){
			def.load();
		}else{
		}

	},
	warunekZapisu : function(rec){
		if(rec.nazwa === "" && rec.symbol === '' ){
			return false;
		}
		return true;
	}
});