/**
 * @task 2014-10-30 Zamiana response.ret >>> response.code
 * @task 2014-10-30 Dodanie do response tablicy "err" informującej o błędach rozpoznanych indywidualnie dla każdej encji podczas przetwarzania przez BusinessLogic
 * @task 4.2.0
 */
Ext.define('StanowiskaLiderowKooperantowStore',{
	extend : 'Ext.data.Store',
	model : StanowiskaModel,
	autoLoad : true,
	autoSync : true,
	autoSave : false,
	idProperty : 'id',

	constructor : function(){
		var def = this;

		Ext.apply(def,{
			proxy:{
				type: 'ajax',
				method : 'POST',
				api : {
					read: '../server/ajax/stanowiska-liderow-kooperantow.php?action=read'
				},
				listeners : {
					exception: function(proxy, response, operation,eOpts){
						var resp = Ext.decode(response.responseText);
						switch(operation.action){
							case 'read':
								switch(resp.success){

								}
								break;
						}
					}
				},
				reader : {
					type : 'json',
					rootProperty : 'data',
					totalProperty : 'countTotal'
				}
			}

		});
		def.superclass.constructor.call(def,arguments);
	}
});