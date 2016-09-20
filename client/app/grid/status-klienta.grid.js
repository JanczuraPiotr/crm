/**
 * @task 4.2.0
 */
Ext.define('StatusKlientaGrid',{
	extend : 'Ext.grid.Panel',
	xtype : 'status-klienta-grid',

	initComponent : function(){
		console.log('StatusKlientaGrid::initComponent');
		var thisSKG = this;

		thisSKG.StatusKlientaStore = new Ext.create('StatusKlientaStore');

		Ext.apply(this,{
				pageSize : 10,
				height : 400,
				width :400,
				store : thisSKG.StatusKlientaStore,
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
						text : 'status ',
						dataIndex : 'status',
						width : 40,
						editor : {
							format : '0'
						}
					},{
						text : 'opis',
						dataIndex : 'opis',
						width : 220
					}
				], // columns
				bbar : [
					{
						xtype: 'pagingtoolbar',
						dock: 'bottom',
						store : thisSKG.StatusKlientaStore,
						pageSize : 30,
						displayInfo: true
					}
				]
		});
		thisSKG.callParent();
	}
});
