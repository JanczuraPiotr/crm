/**
 * @task 4.2.0
 */
Ext.define('PracaZZadaniamiWindow',{
	extend : 'Ext.window.Window',
	xtype : 'praca-z-zadaniami-window',
	resizable : false,
	collapsible : true,
	layout : 'hbox',
	title : 'praca z zadaniami',

	constructor : function(){
		console.log('PracaZZadaniamiWindow::constructor()');
		var def = this;

		def.AktaSprawyPanel = new Ext.create('AktaSprawyPanel').setDisabled(true);
		def.KrokiZadaniaGrid = new Ext.create('KrokiZadaniaGrid').setDisabled(true);

		def.ZadaniaNaglowekMiniGrid = new Ext.create('ZadaniaNaglowekMiniGrid',{
			listeners : {
				select : function( thiss, record, index, eOpts ){
					def.AktaSprawyPanel.setNrZadania(record.data.nr_zadania);
					def.AktaSprawyPanel.setOpisProduktu(record.data.produkt_opis);
					def.KrokiZadaniaGrid.setNrZadania(record.data.nr_zadania);
					def.KrokiZadaniaGrid.setOpisProduktu(record.data.produkt_opis);
				},
				deselect : function( thiss, record, index, eOpts ){
					def.AktaSprawyPanel.setNrZadania(0);
					def.AktaSprawyPanel.setOpisProduktu('');
					def.KrokiZadaniaGrid.setNrZadania(0);
					def.KrokiZadaniaGrid.setOpisProduktu('');
				}
			}
		});

		def.ZadaniaNaglowekMiniGrid.setStanowiskoId(CRM.stanowisko_id);
		def.KrokiZadaniaGrid.addLinked(def.AktaSprawyPanel);
		def.KrokiZadaniaGrid.addLinked(def.ZadaniaNaglowekMiniGrid);

		Ext.apply(def,{
			items : [
				def.ZadaniaNaglowekMiniGrid,
				def.KrokiZadaniaGrid,
				def.AktaSprawyPanel
			]
		});

		def.superclass.constructor.call(def,arguments);
	},
	listeners : {
		close : function(panel,eOpts){
			PracaZZadaniamiWindow = null;
		}
	}

});

var PracaZZadaniamiWindow = null;