/**
 * Zespół kontrolek do nadzoru przez zarząd nad pracą z zadaniami w podległej jednostce.
 *
 * namespace client\app\view
 * use client\app\panel\AktaSprawyPanel
 * use client\app\view\KrokiZadaniaPreviewGrid
 * use client\app\view\ZadaniaNaglowekMiniGrid
 *
 * @task 4.2.0
 */
Ext.define('NadzorNadZadaniamiPanel',{
	extend : 'Ext.panel.Panel',
	width : 400,
	height : 600,

	constructor : function(){
		var def = this;
		def.stanowisko_id = -1;
		def.AktaSprawyPanel = new Ext.create('AktaSprawyPanel').setDisabled(true);
		def.KrokiZadaniaPreviewGrid = new Ext.create('KrokiZadaniaPreviewGrid').setDisabled(true);

		def.ZadaniaNaglowekMiniGrid = new Ext.create('ZadaniaNaglowekMiniGrid',{
			listeners : {
				activate : function( tthis, eOpts ){
				},
				select : function( thiss, record, index, eOpts ){
					 def.AktaSprawyPanel.setNrZadania(record.data.nr_zadania);
					 def.AktaSprawyPanel.setOpisProduktu(record.data.produkt_opis);
					 def.KrokiZadaniaPreviewGrid.setNrZadania(record.data.nr_zadania);
					 def.KrokiZadaniaPreviewGrid.setOpisProduktu(record.data.produkt_opis);
				},
				deselect : function( thiss, record, index, eOpts ){
					 def.AktaSprawyPanel.setNrZadania(0);
					 def.AktaSprawyPanel.setOpisProduktu('');
					 def.KrokiZadaniaPreviewGrid.setNrZadania(0);
					 def.KrokiZadaniaPreviewGrid.setOpisProduktu('');
				}
			}
		});

		 def.setStanowiskoId(0);
		 def.KrokiZadaniaPreviewGrid.addLinked( def.AktaSprawyPanel);
		 def.KrokiZadaniaPreviewGrid.addLinked( def.ZadaniaNaglowekMiniGrid);

		Ext.apply( def,{
			layout : 'hbox',
			items : [
				 def.ZadaniaNaglowekMiniGrid,
				 def.KrokiZadaniaPreviewGrid,
				 def.AktaSprawyPanel
			]

		});

		 def.superclass.constructor.call( def,arguments);

	},
	setStanowiskoId : function(stanowisko_id){
		var def = this;

		if(def.stanowisko_id !== stanowisko_id){
			def.stanowisko_id = stanowisko_id;
//			def.ZadaniaNaglowekMiniGrid.getStore().remoteFilter = true;

			if(def.stanowisko_id < 1){
				def.AktaSprawyPanel.setNrZadania(0);
				def.AktaSprawyPanel.setOpisProduktu('');
				def.KrokiZadaniaPreviewGrid.setNrZadania(0);
				def.KrokiZadaniaPreviewGrid.setOpisProduktu('');
				def.ZadaniaNaglowekMiniGrid.getStore().setStanowiskoId(0);
				def.ZadaniaNaglowekMiniGrid.setDisabled(true);
			}else{
				def.ZadaniaNaglowekMiniGrid.getStore().setStanowiskoId(def.stanowisko_id);
				def.ZadaniaNaglowekMiniGrid.enable();
			}
		}
	}
});