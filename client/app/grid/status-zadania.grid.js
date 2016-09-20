/**
 * @task 4.2.0
 */
Ext.define('StatusZadaniaGrid',{
	extend : 'Ext.grid.Panel',
	xtype : 'status-zadania-grid',

	initComponent : function(){
		var thisSKG = this;
		thisSKG.StatusZadaniaStore = new Ext.create('StatusZadaniaStore');

		Ext.apply(this,{
				pageSize : 10,
				height : 400,
				width :400,
				store : thisSKG.StatusZadaniaStore,
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
						store : thisSKG.StatusZadaniaStore,
						pageSize : 30,
						displayInfo: true
					}
				]
		});
		thisSKG.callParent();
	}
});
