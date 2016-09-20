/**
 * @task 4.2.0
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