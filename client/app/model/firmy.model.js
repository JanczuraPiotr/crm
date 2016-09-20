/**
 * @task 4.2.0
 */
var FirmyFields = [
	{
		name : 'id',
		type : 'int'
	},{
		name : 'symbol',
		type : 'string'
	},{
		name : 'nazwa',
		type : 'string'
	},{
		name : 'nip',
		type : 'string'
	},{
		name : 'kod_poczt',
		type : 'string'
	},{
		name : 'miejscowosc',
		type : 'string'
	},{
		name : 'ul',
		type : 'string'
	},{
		name : 'nr_b',
		type : 'string'
	},{
		name : 'nr_l',
		type : 'string'
	},{
		name : 'tel',
		type : 'string'
	},{
		name : 'email',
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

Ext.define('FirmyModel',{
	extend : 'Ext.data.Model',
	fields : FirmyFields,
	idProperty : 'id'
});