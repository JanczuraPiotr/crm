/**
 * @task 4.2.0
 */
var WiazanieFirmBankowFields = [
	{
		name : 'id',
		type : 'int'
	},{
//		name : 'firma_id',
//		type : 'int'
//	},{
		name : 'bank_id',
		type : 'int',
		defaultValue : null
	},{
		name : 'firma_oddzial_id',
		type : 'int',
		defaultValue : null
	},{
		name : 'bank_oddzial_id',
		type : 'int',
		defaultValue : null
	},{
		name : 'bank_symbol',
		type : 'string',
		defaultValue : ''
	},{
		name : 'bank_nazwa',
		type : 'string',
		defaultValue : ''
	},{
		name : 'bank_oddzial_symbol',
		type : 'string',
		defaultValue : ''
	},{
		name : 'bank_oddzial_nazwa',
		type : 'string',
		defaultValue : ''
	},{
		name : 'bank_oddzial_miejscowosc',
		type : 'string',
		defaultValue : ''
	},{
		name : 'bank_oddzial_ul',
		type : 'string',
		defaultValue : ''
	},{
		name : 'bank_oddzial_nr_b',
		type : 'string',
		defaultValue : ''
	},{
		name : 'bank_oddzial_nr_l',
		type : 'string',
		defaultValue : ''
	},{
		name : 'data_od',
		type : 'date',
		defaultValue : null,
		dateFormat : 'Y-m-d'
	},{
		name : 'data_do',
		type : 'date',
		defaultValue : null,
		dateFormat : 'Y-m-d'
	}
];

Ext.define('WiazanieFirmBankowModel',{
	extend : 'Ext.data.Model',
	fields : WiazanieFirmBankowFields,
	idProperty : 'id'
});

