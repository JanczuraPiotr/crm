/**
 * @work 4.2.0
 */
Ext.define('CRM.grid.BankiMin',{
	extend : 'Ext.grid.Panel',
	autoLoad : false,
	autoSync : true,
	autoSave : false,
	idProperty : 'id',

	requires : [
		'CRM.store.Banki'
	],

	initComponent : function(){
		var def = this;

		def.BankiStore = new Ext.create('CRM.store.Banki');

		Ext.apply(this,{
				pageSize : 10,
//				height : 400,
				width : 250,
				title : 'Banki',
				store : def.BankiStore,
				columns:[
					{
						text : 'symbol',
						dataIndex : 'symbol',
						width : 50
					},{
						text : 'nazwa',
						dataIndex : 'nazwa',
						width : 180
					}
				], // columns
				bbar : [
					{
						xtype: 'pagingtoolbar',
						dock: 'bottom',
						store : def.BankiStore,
						pageSize : 30,
						displayMsg : '',
						displayInfo: false
					}
				] // bbar
		});
		def.callParent();
	}
});
