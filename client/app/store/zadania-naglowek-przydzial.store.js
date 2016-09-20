/**
 * @task 4.2.0
 * @task 2014-10-30 Dodanie do response tablicy "err" informującej o błędach rozpoznanych indywidualnie dla każdej encji podczas przetwarzania przez BusinessLogic
 */
Ext.define('ZadaniaNaglowekPrzydzialStore',{
	extend : 'Ext.data.Store',
	model : ZadaniaNaglowekModel,
	autoLoad : true,
	autoSync : true,
	autoSave : false, // tylko podgląd
	idProperty : 'id',
	stanowisko_id : -1,
	remoteFilter : true,

	proxy : {
		type : 'ajax',
		method : 'POST',
		api : {
			read : '../server/ajax/przydzial-zadan.php?action=read'
		},
		reader : {
			type : 'json',
			rootProperty : 'data',
			totalProperty : 'countTotal'
		}
	},
	autoLoad : {
		start : 0,
		limit : 10000
	},
	/**
	 * Gdy stanowisko_id == null || stanowisko_id > 0 nastąpi filtrowanie. Gdy stanowisko_id <= 0 filtr jest wyłączony.
	 * @param {int|null} stanowisko_id
	 */
	setStanowiskoId : function(stanowisko_id){
		var def = this;

		if(def.stanowisko_id !== stanowisko_id){
			def.stanowisko_id = stanowisko_id;
			if(def.stanowisko_id > 0 || stanowisko_id === null){
				def.clearFilter(true);
				def.filter({
					property : 'stanowisko_id',
					operator : '=',
					value    : def.stanowisko_id
				});
			}else{
				def.clearFilter();
			}
		}
	}
});