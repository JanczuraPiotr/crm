/**
 * @done 4.2.0
 */
var KlienciFields = [
	{
		name : 'id',
		type : 'int'
	},{
		name : 'nazwa',
		type : 'string'
	},{
		name : 'imie',
		type : 'string'
	},{
		name : 'pesel',
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
		name : 'email',
		type : 'string'
	},{
		name : 'telkom',
		type : 'string'
	},{
		name : 'teldom',
		type : 'string'
	},{
		name : 'telpraca',
		type : 'string'
	},{
		name : 'opis',
		type : 'string'
	},{
		name : 'pochodzenie_klientow_id',
		type : 'int'
	},{
		name : 'firma_id',
		type : 'int'
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

Ext.define('CRM.model.Klienci',{
	extend : 'Ext.data.Model',
	fields : KlienciFields,
	idProtperty : 'id'
});