/**
 * @prace 2014-09-24 extjs 4.2.2 >> extjs 5.0.1
 */
Ext.define('PracownicyWindow',{
	extend : 'Ext.window.Window',
	title : 'Pracownicy firmy',
	collapsible : true,
	layout : 'hbox',

	constructor : function(){
		var def = this;

		def.PracownicyGrid = new Ext.create('PracownicyGrid');
		def.PracownicyGrid.title = '';
		def.PracownicyGrid.setFirmaId(CRM.firma_id);

		def.superclass.constructor.call(def, arguments);
	},

	initComponent : function(){
		var def  = this;

		Ext.apply(def,{
			items : [
				def.PracownicyGrid
			]
		});
		def.callParent();
	},
	listeners : {
		close : function(panel, eOpts){
			PracownicyWindow = null;
		}
	}
});

var PracownicyWindow = null; // @todo przepisać na singletona w konstruktorze