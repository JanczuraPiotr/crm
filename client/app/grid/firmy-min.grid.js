/**
 * @task 4.2.0
 */
Ext.define('FirmyMinGrid',{
	extend : 'Ext.grid.Panel',
	xtype : 'firmy-grid',
	autoLoad : false,
	autoSync : true,
	autoSave : false,
	idProperty : 'id',

	initComponent : function(){
		var FMG = this;
		FMG.height = 600;
		FMG.width = 245;

		FMG.FirmyStore = new Ext.create('FirmyStore');

		Ext.apply(FMG,{
				pageSize : 10,
				title : 'Firmy',
				store : FMG.FirmyStore,
				columns:[
					{
						text : 'symbol',
						dataIndex : 'symbol',
						width : 60
					},{
						text : 'nazwa',
						dataIndex : 'nazwa',
						width : 165
					}
				], // columns
				bbar : [
					{
						xtype: 'pagingtoolbar',
						dock: 'bottom',
						store : FMG.FirmyStore,
						pageSize : 30,
						displayMsg : '',
						displayInfo: false
					}
				] // bbar
		});
		FMG.callParent();
	}
});
