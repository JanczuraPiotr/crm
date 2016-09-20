/**
 * @task 4.2.0
 */
Ext.define('PochodzenieKlientowWindow',{
	xtype : 'pochodzenie-klientow-window',
	extend : 'Ext.window.Window',
	title : 'Słownik pochodzenia klientów',
	collapsible : true,

	constructor : function(){
		var def = this;

		def.PochodzenieKlientowGrid = new Ext.create('PochodzenieKlientowGrid');

		def.callParent(arguments);
	},

	initComponent : function(){
		var def = this;

		Ext.apply(def,{
			items : [
					def.PochodzenieKlientowGrid
			],
			resizable : false
		});
		def.callParent();
	},
	
	listeners : {
		close : function(panel , eOpts){
			PochodzenieKlientowWindow = null;
		}
	}
});

var PochodzenieKlientowWindow = null; // @todo przepisać na singletona w konstruktorze