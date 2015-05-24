/**
 * @confirm 2015-01-02 ExtJS 5.1.0
 */
Ext.define('BankiMinGrid',{
	extend : 'Ext.grid.Panel',
	xtype : 'banki-grid',
	autoLoad : false,
	autoSync : true,
	autoSave : false,
	idProperty : 'id',

	initComponent : function(){
		var def = this;

		def.BankiStore = new Ext.create('BankiStore');

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
