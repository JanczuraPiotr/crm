/**
 * @work 2014-09-24 extjs 4.2.2 >> extjs 5.0.1
 */
Ext.define('StatusStanowiskaGrid',{
	extend : 'Ext.grid.Panel',
	xtype : 'status-stanowiska-grid',

	initComponent : function(){
		var SKG = this;

		SKG.StatusStanowiskaStore = new Ext.create('StatusStanowiskaStore');

		Ext.apply(this,{
				pageSize : 10,
				height : 400,
				width :350,
				store : SKG.StatusStanowiskaStore,
				columns:[
					{
						text : 'id',
						dataIndex : 'id',
						width : 30
					},{
						text : 'symbol',
						dataIndex : 'symbol',
						width : 80
					},{
						text : 'opis',
						dataIndex : 'opis',
						width : 220
					}
				],
				bbar : [
					{
						xtype: 'pagingtoolbar',
						dock: 'bottom',
						store : SKG.StatusStanowiskaStore,
						pageSize : 30,
						displayInfo: false
					}
				]
		});
		SKG.callParent();
	}
});
