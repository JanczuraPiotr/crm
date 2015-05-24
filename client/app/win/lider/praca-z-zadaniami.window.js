/**
 * @prace 2014-09-24 extjs 4.2.2 >> extjs 5.0.1
 */
Ext.define('PracaZZadaniamiWindow',{
	extend : 'Ext.window.Window',
	xtype : 'praca-z-zadaniami-window',
	resizable : false,
	collapsible : true,
	layout : 'hbox',
	title : 'praca z zadaniami',

	constructor : function(){
		var thisPZZW = this;

		thisPZZW.AktaSprawyPanel = new Ext.create('AktaSprawyPanel').setDisabled(true);
		thisPZZW.KrokiZadaniaGrid = new Ext.create('KrokiZadaniaGrid').setDisabled(true);

		thisPZZW.ZadaniaNaglowekMiniGrid = new Ext.create('ZadaniaNaglowekMiniGrid',{
			listeners : {
				select : function( thiss, record, index, eOpts ){
					thisPZZW.AktaSprawyPanel.setNrZadania(record.data.nr_zadania);
					thisPZZW.AktaSprawyPanel.setOpisProduktu(record.data.produkt_opis);
					thisPZZW.KrokiZadaniaGrid.setNrZadania(record.data.nr_zadania);
					thisPZZW.KrokiZadaniaGrid.setOpisProduktu(record.data.produkt_opis);
				},
				deselect : function( thiss, record, index, eOpts ){
					thisPZZW.AktaSprawyPanel.setNrZadania(0);
					thisPZZW.AktaSprawyPanel.setOpisProduktu('');
					thisPZZW.KrokiZadaniaGrid.setNrZadania(0);
					thisPZZW.KrokiZadaniaGrid.setOpisProduktu('');
				}
			}
		});

		thisPZZW.ZadaniaNaglowekMiniGrid.getStore().remoteFilter = true;
		thisPZZW.ZadaniaNaglowekMiniGrid.getStore().filter('stanowisko_id',CRM.stanowisko_id);
		thisPZZW.KrokiZadaniaGrid.addLinked(thisPZZW.AktaSprawyPanel);
		thisPZZW.KrokiZadaniaGrid.addLinked(thisPZZW.ZadaniaNaglowekMiniGrid);

		Ext.apply(thisPZZW,{
			items : [
				thisPZZW.ZadaniaNaglowekMiniGrid,
				thisPZZW.KrokiZadaniaGrid,
				thisPZZW.AktaSprawyPanel
			]
		});

		thisPZZW.superclass.constructor.call(thisPZZW,arguments);
	},
	listeners : {
		close : function(panel,eOpts){
			PracaZZadaniamiWindow = null;
		}
	}

});

var PracaZZadaniamiWindow = null;