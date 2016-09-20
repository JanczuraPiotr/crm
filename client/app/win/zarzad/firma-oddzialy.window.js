/**
 * @work 4.2.0
 */
Ext.define('FirmaOddzialyWindow',{
	extend : 'Ext.window.Window',
	title : 'Firma i jej oddziały',
	collapsible : true,

	constructor : function(){
		var def = this;
		def.FirmyOddzialyGrid = new Ext.create('FirmyOddzialyGrid');
		def.FirmyOddzialyGrid.height = 300;
		def.FirmyOddzialyGrid.setFirmaId(CRM.firma_id);
		def.FirmaGrid = Ext.create('FirmaGrid');
		def.callParent(arguments);
		//def.superclass.constructor.call(def, arguments);
	},

	initComponent : function(){
		var def = this;
		Ext.apply(def,{
			items : [
					def.FirmaGrid,
					def.FirmyOddzialyGrid
			],
			resizable : false
		});
		def.callParent();
	},

	listeners : {
		close : function(thet , eOpts){
			delete FirmaOddzialyWindow;
			FirmaOddzialyWindow = null;
		}
	}
});

var FirmaOddzialyWindow = null;