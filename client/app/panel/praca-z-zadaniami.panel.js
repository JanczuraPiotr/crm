/**
 * Zespół kontrolek do pracy z zadaniami przypisanymi do stanowiska
 * Dane klienta należy podać jako obiekt Model przekazany w obiekcie konfiguracyjnym pod zmienną recKlient
 * @done 2014-08-28
 * @task 4.2.0
 */
Ext.define('PracaZZadaniamiPanel',{
	extend : 'Ext.panel.Panel',
	width : 400,
	height : 600,

	constructor : function(){
		var def = this;
		def.stanowisko_id = -1;
		def.AktaSprawyPanel = new Ext.create('AktaSprawyPanel').setDisabled(true);
		def.KrokiZadaniaGrid = new Ext.create('KrokiZadaniaGrid').setDisabled(true);
		def.ZadaniaNaglowekMiniGrid = new Ext.create('ZadaniaNaglowekMiniGrid',{
			listeners : {
				select : function( object, record, index, eOpts ){
					def.AktaSprawyPanel.setNrZadania(record.data.nr_zadania);
					def.AktaSprawyPanel.setOpisProduktu(record.data.produkt_opis);
					def.KrokiZadaniaGrid.setNrZadania(record.data.nr_zadania);
					def.KrokiZadaniaGrid.setOpisProduktu(record.data.produkt_opis);
				},
				deselect : function( object, record, index, eOpts ){
					def.AktaSprawyPanel.setNrZadania(0);
					def.AktaSprawyPanel.setOpisProduktu('');
					def.KrokiZadaniaGrid.setNrZadania(0);
					def.KrokiZadaniaGrid.setOpisProduktu('');
				}
			}
		});

		def.setStanowiskoId(CRM.stanowisko_id);
		def.KrokiZadaniaGrid.addLinked(def.AktaSprawyPanel);
		def.KrokiZadaniaGrid.addLinked(def.ZadaniaNaglowekMiniGrid);

		Ext.apply(def,{
			layout : 'hbox',
			items : [
				def.ZadaniaNaglowekMiniGrid,
				def.KrokiZadaniaGrid,
				def.AktaSprawyPanel
			]

		});

		def.superclass.constructor.call(def,arguments);

	},
	setStanowiskoId : function(stanowisko_id){
		var def = this;
		if(def.stanowisko_id !== stanowisko_id){
			def.stanowisko_id = stanowisko_id;
			def.ZadaniaNaglowekMiniGrid.getStore().remoteFilter = true;
			def.ZadaniaNaglowekMiniGrid.getStore().filter('stanowisko_id',def.stanowisko_id);
			def.ZadaniaNaglowekMiniGrid.getStore().load();
			if(def.stanowisko_id < 1){
				def.AktaSprawyPanel.setNrZadania(0);
				def.AktaSprawyPanel.setOpisProduktu('');
				def.KrokiZadaniaGrid.setNrZadania(0);
				def.KrokiZadaniaGrid.setOpisProduktu('');
				def.ZadaniaNaglowekMiniGrid.setDisabled(true);
			}else{
				def.ZadaniaNaglowekMiniGrid.enable();
			}
		}
	}
});