//"use strict";
/**
 * Kontrolka wyświetlana gdy potrzeba wybrać oddział firmy
 * @task 4.2.0
 * namespace client\app\view
 * use client\app\store\StanowiskaStore
 * use client\app\store\StatusStanowiskaStore
 *
 */
Ext.define('StanowiskaMiniGrid',{
	extend : 'Ext.grid.Panel',

	constructor : function(){
		var def = this;

		def.firma_id = -1;
		def.placowka_id = -1;

		def.StanowiskaStore = new Ext.create('StanowiskaStore');
		def.StatusyStanowiskStore = new Ext.create('StatusStanowiskaStore');

		Ext.apply(def,{
				pageSize : 10,
				title : 'Stanowiska w oddziale',
				width : 250,
				height : 600,
				store : def.StanowiskaStore,
				columns : [
					{
						text : 'stanowisko',
						width : 120,
						renderer : function(value, metaData, record, row, col, store, gridView){
							if(record.data.pracownik.length > 0){
								return record.data.nazwa+'<br> <i>['+record.data.pracownik+']</i>';
							}else{
								return record.data.nazwa+'<br> <i>[Wakat]</i>';
							}
						}
					},{
						text : 'telefon',
						dataIndex : 'tel',
						width : 70
					},{
						text : 'status',
						dataIndex : 'status_stanowiska_id',
						width : 40,
						renderer : function(value){
							return def.StatusyStanowiskStore.getById(value).get('symbol');
						}
					}
				],
				bbar : [
					{
						xtype: 'pagingtoolbar',
						dock: 'bottom',
						scope : def,
						store : def.StanowiskaStore,
						pageSize : 30,
						displayInfo: false
					}
				], // bbar
				plugins:[
				]
		});

		def.superclass.constructor.call(def, arguments);
		def.setDisabled(true);
	},

	onSelectChange: function(selModel, selections){
		var def = this;
	},
	setPlacowkaNazwa : function(nazwa){
		var def = this;
		def.setTitle('Stanowisko w oddziale : '+nazwa);
	},
	setPlacowka : function(firma_id, placowka_id){
		var def = this;
		def.firma_id = firma_id;
		def.placowka_id = placowka_id;
		def.StanowiskaStore.setPlacowkaId(placowka_id);
		if(placowka_id > 0){
			def.enable();
		}else{
			def.setDisabled(true);
			def.setTitle('Wybierz oddział firmy');
		}
	}
});
