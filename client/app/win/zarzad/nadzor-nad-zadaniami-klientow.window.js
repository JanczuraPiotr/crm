/**
 * @task 4.2.0
 */
Ext.define('NadzorNadZadaniamiKlientowWindow',{
	extend : 'Ext.window.Window',
	title : 'nadzór nad zadaniami - według stanowisk',
	resizable : false,
	collapsible : true,

	constructor : function(){
		var def = this;
		def.firma_id = CRM.firma_id;
		def.placowka_id = -1;

		def.NadzorKlientowGrid = new Ext.create('NadzorKlientowGrid',{
		});

//		def.NadzorNadZadaniamiPanel = new Ext.create('NadzorNadZadaniamiPanel',{
//			height : 660
//		});
//		def.NadzorKlientowGrid.wybranoStanowiskoPracy = function(stanowisko_id){
//			def.NadzorNadZadaniamiPanel.setStanowiskoId(stanowisko_id);
//		};
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
					title : 'lista klientow',
					region : 'west',
					layout : 'fit',
//					width : 499,
					items : [
						def.NadzorKlientowGrid
					]
//				},{
//					title : 'zadania',
//					region : 'center',
//					collapsible : false,
//					layout : 'fit',
//					items : [
//						def.NadzorNadZadaniamiPanel
//					]
				}
			]
		});

		def.callParent();
	},

	listeners : {
		close : function(panel, eOpts){
			NadzorNadZadaniamiKlientowWindow = null;
		}
	}

});

var NadzorNadZadaniamiKlientowWindow = null;