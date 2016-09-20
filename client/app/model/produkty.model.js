/**
 * @task 4.2.0
 */
var ProduktyFields = [
	{
		name : 'id',
		type : 'int'
	},{
		name : 'bank_id',
		type : 'int'
	},{
		name : 'symbol',
		type : 'string'
	},{
		name : 'nazwa',
		type : 'string'
	},{
		name : 'opis',
		type : 'string'
	},{
		name : 'data_od',
		type : 'date',
		dateFormat : 'Y-m-d'
	},{
		name : 'data_do',
		type : 'date',
		dateFormat : 'Y-m-d'
	}
];
Ext.define('ProduktyModel',{
	extend : 'Ext.data.Model',
	xtype : 'produkty-model',
	alias : 'produkty-model',
	fields : ProduktyFields,
	idProperty : 'id'
});