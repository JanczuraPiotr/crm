/**
 * @prace 2014-09-24 extjs 4.2.2 >> extjs 5.0.1
 */
Ext.define('StatusStanowiskaWindow',{
	xtype : 'status-stanowiska-window',
	extend : 'Ext.window.Window',
	title : 'Typy stanowisk pracy',
	collapsible : true,

	constructor : function(){
		var thisSKW = this;
		thisSKW.StatusStanowiskaGrid = new Ext.create('StatusStanowiskaGrid');
		thisSKW.superclass.constructor.call(thisSKW, arguments);
	},

	initComponent : function(){
		var thisSKW = this;

		Ext.apply(thisSKW,{
			items : [
					thisSKW.StatusStanowiskaGrid
			],
			resizable : false
		});
		thisSKW.callParent();
	},
	listeners : {
		close : function(panel, eOpts){
			StatusStanowiskaWindow = null;
		}
	}
});


var StatusStanowiskaWindow = null; // @todo przepisać na singletona w konstruktorze