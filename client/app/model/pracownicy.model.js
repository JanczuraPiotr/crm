var PracownicyFields = [
	{
		name : 'id',
		type : 'int'
	},{
		name : 'firma_id',
		type : 'int'
	},{
		name : 'nazwisko',
		type : 'string'
	},{
		name : 'imie',
		type : 'string'
	},{
		name : 'pesel',
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

Ext.define('PracownicyModel',{
	extend : 'Ext.data.Model',
	fields : PracownicyFields,
	idProperty : 'id'
})