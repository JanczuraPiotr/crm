//"use strict"; //callParent()
/**
 * Panel pozwalający wybrać oddział firmy i stanowisko pracy.
 * Kontrolka przeznaczona jest dla prezesów by umożliwić wybór stanowiska w wybranej firmie.
 * Na tak wybranym stanowisku mogą być prowadzone prace zeleżne od kontekstu użycia tego panelu.
 *
 * Klasa definiuje zdarzenia:
 *	- selectworkplace którego parametrem jest identyfikator oznaczonego stanowiska pracy : stanowisko_id
 *	-	deselectworkplace którego parametrem jest identyfikator stanowiska które utrzaciło zaznaczenie : stanowisko_id
 *
 * namespace client\app\panel\zarzad
 * use client\app\view\StanowiskaMiniGrid
 * use client\app\view\FirmyOddzialyMinGrid
 *
 * @task 4.2.0
 */
Ext.define('WyborStanowiskaPracyPanel',{
	extend : 'Ext.panel.Panel',

	constructor : function(config){
		var def = this;
		def.firma_id = CRM.firma_id;
		def.placowka_id = -1;
		def.recStanowisko = null;

		def.StanowiskaMiniGrid = new Ext.create('StanowiskaMiniGrid');
		def.StanowiskaMiniGrid.on('select', function( thet, record, index, eOpts ){
			def.recStanowisko = record;
			def.fireEvent('selectworkplace',record);
		});
		def.StanowiskaMiniGrid.on('deselect', function( thet, record, index, eOpts ){
			def.fireEvent('deselectworkplace',def.recStanowisko);
			def.recStanowisko = null;
		});

		def.FirmyOddzialyMinGrid = new Ext.create('FirmyOddzialyMinGrid');
		def.FirmyOddzialyMinGrid.on('select',function( thet, record, index, eOpts ){
			def.placowka_id = record.data.id;
			def.StanowiskaMiniGrid.setPlacowka(def.firma_id,def.placowka_id);
			def.StanowiskaMiniGrid.setPlacowkaNazwa(record.data.nazwa);
			def.fireEvent('deselectworkplace',def.recStanowisko);
			def.recStanowisko = null;
		});
		def.FirmyOddzialyMinGrid.on('deselect',function( thet, record, index, eOpts ){
			def.placowka_id = 0;
			def.fireEvent('deselectworkplace',def.recStanowisko);
			def.recStanowisko = null;
		});

		def.callParent(arguments); // nie pracuje w strict
	},
	initComponent : function(){
		var def = this;

		Ext.apply(def,{
			width : 600,
			layout : 'hbox',
			items : [
				def.FirmyOddzialyMinGrid,
				def.StanowiskaMiniGrid
			]
		});

		def.callParent();
	}
});