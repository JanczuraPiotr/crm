/**
 * @prace 2014-09-24 extjs 4.2.2 >> extjs 5.0.1
 */
Ext.define('StatusKlientaWindow',{
	xtype : 'status-klienta-window',
	extend : 'Ext.window.Window',
	title : 'Słownik statusów klienta',
	collapsible : true,

	constructor : function(){
		console.log('StatusKlientaWindow::constructor');
		var thisSKW = this;

		thisSKW.StatusKlientaGrid = new Ext.create('StatusKlientaGrid');

//		thisPKW.callParent();
		thisSKW.superclass.constructor.call(thisSKW, arguments);
	},

	initComponent : function(){
		var thisSKW = this;

		Ext.apply(thisSKW,{
			items : [
					thisSKW.StatusKlientaGrid
			],
			resizable : false
		});
		thisSKW.callParent();
	},
	listeners : {
		close : function(panel, eOpts){
			StatusKlientaWindow = null;
		}
	}
});

var StatusKlientaWindow = null; // @todo przepisać na singletona w konstruktorze