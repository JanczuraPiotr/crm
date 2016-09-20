/**
 * @task 4.2.0
 */
Ext.define('DokumentyProduktuGrid',{
	extend : 'Ext.grid.Panel',
	xtype : 'dokumenty-produkty-grid',
	title : 'DOKUMENTY PRODUKTU:',

	initComponent : function(){
		console.log('DokumentyProduktuGrid::initComponent()');
		var def = this;
		def.produkt_id = -1;

		def.DokumentyProduktuStore = new Ext.create('DokumentyProduktuStore');

		Ext.apply(def,{
			pageSize : 10,
			store : def.DokumentyProduktuStore,

			columns:[
				{
//					text : 'id',
//					dataIndex : 'id',
//					width : 30
//				},{
					xtype : 'checkcolumn',
					text : 'wymagany',
					dataIndex : 'wymagany',
					width : 70
				},{
					text : 'symbol',
					dataIndex : 'symbol',
					width : 100
				},{
					text : 'nazwa',
					dataIndex : 'nazwa',
					width : 250
				}
			], // columns

			bbar : [
				{
					xtype: 'pagingtoolbar',
					dock: 'bottom',
					store : def.DokumentyProduktuStore,
					pageSize : 30,
					displayInfo: true
				}
			] // bbar
		});
		def.callParent();
	},
	setProduktId : function(produkt_id){
		var def = this;
		console.log('DokumentyProduktuGrid::setProduktId');
		console.log(produkt_id);

		def.produkt_id = produkt_id;
		def.DokumentyProduktuStore.setProduktId(produkt_id);
		if(produkt_id > 0){
		}else{
		}

	}
});
