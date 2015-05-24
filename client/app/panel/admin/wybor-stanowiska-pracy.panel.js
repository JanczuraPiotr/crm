//"use strict"; callParent()
/**
 * Panel pozwalający wybrać firmę, oddział w firmie i stanowisko w oddziale.
 * Kontolka przeznaczona jest dla właściciela
 * Klasa definiuje zdarzenia:
 *	- selectworkplace którego parametrem jest identyfikator oznaczonego stanowiska pracy : stanowisko_id
 *	-	deselectworkplace którego parametrem jest identyfikator stanowiska które utrzaciło zaznaczenie : stanowisko_id
 * @confirm 2014-08-30
 * namespace client\app\panel\admin
 * use client\app\view\StanowiskaMiniGrid
 * use client\app\view\FirmyOddzialyMinGrid
 * use client\app\view\FirmyMinGrid
 */
Ext.define('WyborStanowiskaPracyPanel',{
	extend : 'Ext.panel.Panel',

	constructor : function(config){
		var def = this;
		def.firma_id = -1;
		def.placowka_id = -1;
		def.recStanowisko = null;
//		def.addEvents('selectworkplace','deselectworkspace');

		def.StanowiskaMiniGrid = new Ext.create('StanowiskaMiniGrid');
		def.StanowiskaMiniGrid.on('select', function( thet, record, index, eOpts ){
			def.recStanowisko = record;
//			def.fireEvent('selectworkplace',def.recStanowisko);
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
			def.StanowiskaMiniGrid.setPlacowka(def.fimra_id,def.placowka_id);
			def.fireEvent('deselectworkplace',def.recStanowisko);
			def.recStanowisko = null;
		});

		def.FirmyMinGrid = new Ext.create('FirmyMinGrid');
		def.FirmyMinGrid.on('select',function( thet, record, index, eOpts ){
			def.firma_id = record.data.id;
			def.placowka_id = 0;
			def.FirmyOddzialyMinGrid.setFirmaId(def.firma_id);
			def.FirmyOddzialyMinGrid.setFirmaNazwa(record.data.nazwa);
			def.StanowiskaMiniGrid.setPlacowka(def.firma_id,def.placowka_id);
			def.fireEvent('deselectworkplace',def.recStanowisko);
			def.recStanowisko = null;
		});
		def.FirmyMinGrid.on('deselect',function( thet, record, index, eOpts ){
			def.firma_id = 0;
			def.placowka_id = 0;
			def.FirmyOddzialyMinGrid.setFirmaId(def.firma_id);
			def.StanowiskaMiniGrid.setPlacowka(def.firma_id,def.placowka_id);
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
				def.FirmyMinGrid,
				def.FirmyOddzialyMinGrid,
				def.StanowiskaMiniGrid
			]
		});
		def.callParent(); //nie pracuje w strict
	}
});