/**
 * @prace 2014-10-30 Zamiana response.ret >>> response.code
 * @prace 2014-10-30 Dodanie do response tablicy "err" informącej o błędach rozpoznanych indywidualnie dla każdej encji podczas przetwarzania przez BusinessLogic
 * @prace 2014-09-24 extjs 4.2.2 >> extjs 5.0.1
 */
Ext.define('StanowiskaLiderowStore',{
	extend : 'Ext.data.Store',
	model : StanowiskaModel,
	autoLoad : false,
	autoSync : true,
	autoSave : false,
	idProperty : 'id',

	constructor : function(){
		var def = this;
		var filter = [];
		def.firma_oddzial_id = 0;

		Ext.apply(def,{
			proxy:{ // PracownicyLiderzyStore::proxy
				type: 'ajax',
				method : 'POST',
				api : {
					read: '../server/ajax/stanowiska-liderow.php?action=read'
				},
				reader : { // PracownicyLiderzyStore::proxy::reader
					type : 'json',
					rootProperty : 'data',
					totalProperty : 'countTotal'
				} // PracownicyLiderzyStore::proxy::reader

			} // PracownicyLiderzyStore::proxy

		});

		def.superclass.constructor.call(def,arguments);
		filter.push({
			property : 'status_stanowiska_id',
			value : 4,
			operator : '='
		});
		filter.push({
			property: 'placowka_id',
			value: def.firma_id,
			operator : '='
		});
		filter.push({
			property:'data_do',
			value: null,
			operator: '='
		});
		def.filter(filter);
	},



	setFirmaOddzial : function(firma_id, firma_oddzial_id){
		var def = this;
		var filter = [];
		def.firma_oddzial_id = firma_oddzial_id;
		def.clearFilter();
		filter.push({
			property : 'status_stanowiska_id',
			value : 4,
			operator : '='
		});
		filter.push({
			property: 'placowka_id',
			value: def.firma_id,
			operator : '='
		});
		filter.push({
			property:'data_do',
			value: null,
			operator: '='
		});
		def.filter(filter);

		if(firma_oddzial_id > 0){
			def.load();
		}else{
		}

	},
	warunekZapisu : function(rec){
		if(rec.nazwisko === "" && rec.pesel === '' ){
			return false;
		}
		return true;
	}
});

//var PracownicyLiderzyStore = Ext.create('PracownicyLiderzyStore');