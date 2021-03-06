
var FirmyOddzialyFields = [
	{
		name : 'id',
		type : 'int'
	},{
		name : 'firma_id',
		type : 'int'
	},{
		name : 'symbol',
		type : 'string'
	},{
		name : 'nazwa',
		type : 'string',
		defaultValue : null
	},{
		name : 'nip',
		type : 'string',
		defaultValue : null
	},{
		name : 'kod_poczt',
		type : 'string',
		defaultValue : null
	},{
		name : 'miejscowosc',
		type : 'string',
		defaultValue : null
	},{
		name : 'ul',
		type : 'string',
		defaultValue : null
	},{
		name : 'nr_b',
		type : 'string',
		defaultValue : null
	},{
		name : 'nr_l',
		type : 'string',
		defaultValue : null
	},{
		name : 'tel',
		type : 'string',
		defaultValue : null
	},{
		name : 'email',
		type : 'string',
		defaultValue : null
	},{
		name : 'data_od',
		type : 'date',
		dateFormat : 'Y-m-d'
	},{
		name : 'data_do',
		type : 'date',
		defaultValue : null,
		dateFormat : 'Y-m-d'
	}
];

Ext.define('FirmyOddzialyModel',{
	extend : 'Ext.data.Model',
	fields : FirmyOddzialyFields,
	idProperty : 'id'
});