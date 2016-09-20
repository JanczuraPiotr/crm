/**
 * @task 4.2.0
 */
var ZespolyFields = [
	{
		name : 'id',
		type : 'int'
	},{
		name : 'lider_id',
		type : 'int'
	},{
		name : 'stanowisko_id',
		type : 'int'
	},{
		name : 'stanowisko_symbol',
		type : 'string',
		defaultValue : null
	},{
		name : 'stanowisko_nazwa',
		type : 'string',
		defaultValue : null
	},{ // telefon na stanowisku dołączonym do grupy
		name : 'stanowisko_tel',
		type : 'string',
		defaultValue : null
	},{ // email na stanowisku dołączonym do grupy
		name : 'stanowisko_email',
		type : 'string',
		defaultValue : null
	},{ // nazwisko i imie pracownika zatrudnionego na stanowisku dołączonym do grupy
		name : 'pracownik_nazwa',
		type : 'string',
		defauleValue : null
	},{
		name : 'pracownik_pesel',
		type : 'string',
		defaultValue : null
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

Ext.define('ZespolyModel',{
	extend : 'Ext.data.Model',
	fields : ZespolyFields,
	idProperty : 'id'
});

