/**
 * @work 2014-09-24 extjs 4.2.2 >> extjs 5.0.1
 */
Ext.define('StatusZadaniaWindow',{
	xtype : 'status-zadania-window',
	extend : 'Ext.window.Window',
	title : 'Słownik statusów zadan',
	collapsible : true,

	constructor : function(){
		var thisSZW = this;
		thisSZW.StatusZadaniaGrid = new Ext.create('StatusZadaniaGrid');
		thisSZW.superclass.constructor.call(thisSZW, arguments);
	},

	initComponent : function(){
		var thisSZW = this;

		Ext.apply(thisSZW,{
			items : [
					thisSZW.StatusZadaniaGrid
			],
			resizable : false
		});
		thisSZW.callParent();
	},
	listeners : {
		close : function(panel, eOpts){
			StatusZadaniaWindow = null;
		}
	}
});

var StatusZadaniaWindow = null; // @todo przepisać na singletona w konstruktorze