/**
 * @work 2014-10-30 Dodanie do response tablicy "err" informącej o błędach rozpoznanych indywidualnie dla każdej encji podczas przetwarzania przez BusinessLogic
 */
Ext.define('StanowiskaZespoluStore',{
	extend : 'Ext.data.Store',
	model : StanowiskoPracownikModel,
	autoLoad : false, // Ładowanie następuje po podaniu identyfikatora lidera dla którego ma być wczytana grupa
	autoSync : false,
	autoSave : false,
	remoteFilter : true,
	idProperty : 'id',
	lider_id : -1,

	proxy : {
		type: 'ajax',
		method : 'POST',
		api : {
			read: '../server/ajax/stanowiska-zespolu.php?action=read',
		},

		listeners : {
//			exception: function(proxy, response, operation,eOpts){
//				var resp = Ext.decode(response.responseText);
//				switch(operation.action){
//					case 'read':
//						switch(resp.success){
//
//						}
//						break;
//				}
//			}
		},
		reader : {
			type : 'json',
			rootProperty : 'data',
			totalProperty : 'countTotal'
		}

	},


	setLiderId : function(lider_id){
		var def = this;
		var filter = [];
		if(def.lider_id !== lider_id){
			def.lider_id = lider_id;
			def.clearFilter();
			filter.push({
				property: 'lider_id',
				value : def.lider_id,
				operation : '='
			});
			filter.push({
				property : 'data_do',
				value: null,
				operator : '='
			});
			def.filter(filter);
			def.load();
		}
	}
});