/**
 * @task 4.2.0
 */
var DokumentyProduktuFields = [
	{
		name : 'id',
		type : 'int'
	},{
		name : 'produkt_id',
		type : 'int'
	},{
		name : 'slownik_id',
		type : 'int'
	},{
		name : 'symbol',
		type : 'string'
	},{
		name : 'nazwa',
		type : 'string'
	},{
		name : 'wymagany',
		type : 'boolean'
	}
];
Ext.define('DokumentyProduktuModel',{
	extend : 'Ext.data.Model',
	xtype : 'dokumenty-produktu-model',
	alias : 'dokumenty-produktu-model',
	fields : DokumentyProduktuFields,
	idProperty : 'id'
});