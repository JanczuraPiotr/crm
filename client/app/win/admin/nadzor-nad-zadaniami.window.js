//"use strict";
/**
 * Okno nadzoru nas zadaniami we wszystkich firmach, z konta właściciela systemu.
 * namespace client\app\win\admin
 * use client\app\view\NadzorNadZadaniamiPanel
 * use client\app\panel\admin\WyborStanowiskaPracyPanel
 * @task 4.2.0
 * @err Zmiana firmy powinna czyścić wszystkie widoki zależne od oddziału firmy
 */
Ext.define('NadzorNadZadaniamiWindow',{
	extend : 'Ext.window.Window',
	title : 'nadzór nad zadaniami - według stanowisk',
	resizable : false,
	collapsible : true,

	constructor : function(){
		var def = this;
		def.firma_id = -1;
		def.placowka_id = -1;

		def.NadzorNadZadaniamiPanel = Ext.create('NadzorNadZadaniamiPanel',{
			height : 660
		});
		def.WyborStanowiskaPracyPanel = Ext.create('WyborStanowiskaPracyPanel');
		def.WyborStanowiskaPracyPanel.on('selectworkplace',function(record){
			def.NadzorNadZadaniamiPanel.setStanowiskoId(record.data.id);
		});
		def.WyborStanowiskaPracyPanel.on('deselectworkplace',function(record){
			def.NadzorNadZadaniamiPanel.setStanowiskoId(0);
		});
		def.callParent(arguments);
	},
	initComponent : function(){
		var def = this;
		Ext.apply(def,{
			width : 1290,
			height : 660,
			layout : 'border',
			defaults : {
				collapsible: true,
				split: false
			},
			items : [
				{
					title : 'wybór stanowiska pracy',
					region : 'west',
					layout : 'fit',
					width : 744,
					items : [
						def.WyborStanowiskaPracyPanel
					]
				},{
					title : 'zadania',
					region : 'center',
					collapsible : false,
					layout : 'fit',
					items : [
						def.NadzorNadZadaniamiPanel
					]
				}
			]

		});

		def.callParent();
	},
	listeners : {
		close : function(panel, eOpts){
			// "singleton"
			NadzorNadZadaniamiWindow = null;
		}
	}

});
// "singleton"
var NadzorNadZadaniamiWindow = null;