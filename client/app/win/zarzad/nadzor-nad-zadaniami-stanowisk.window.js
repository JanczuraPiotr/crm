//"use strict"; // callParent() powoduje błąd
/**
 * Okno nadzoru nad zadaniami w firmie z rozbiciem na oddziały firm i stanowiska
 * namespace  \app\win\zarzad
 * use client\app\panel\NadzorNadZadaniamiPanel
 * use client\app\panel\zarzad\WyborStanowiskaPracyPanel
 * @confirm 2015-01-02 ExtJS 5.1.0
 */
Ext.define('NadzorNadZadaniamiStanowiskWindow',{
	extend : 'Ext.window.Window',
	title : 'Nadzór nad zadaniami według stanowisk pracy', // @todo uzupełnić tytuł okna o nazwę firmy
	resizable : false,
	collapsible : true,

	constructor : function(){
		var def = this;
		def.firma_id = CRM.firma_id;
		def.placowka_id = -1;
		def.stanowisko_id = -1;

		def.NadzorNadZadaniamiPanel = new Ext.create('NadzorNadZadaniamiPanel',{
			height : 660
		});
		def.WyborStanowiskaPracyPanel = new Ext.create('WyborStanowiskaPracyPanel');
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
					width : 499,
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
			NadzorNadZadaniamiStanowiskWindow = null;
		}
	}

});
// "singleton"
var NadzorNadZadaniamiStanowiskWindow = null;