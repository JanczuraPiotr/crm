var LiderzyFields = [
	{
		name : 'id',
		type : 'int',
		defaultValue : null
	},{
		name : 'symbol',
		type : 'string',
		defaultValue : null
	},{
		name : 'nazwa',
		type : 'string',
		defaultValue : null
	},{
		name : 'opis',
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

	},{ // Stanowisko na którym pracuje lider grupy
		name : 'stanowisko_id',
		type : 'int'
	},{
		name : 'placowka_id',
		type : 'int'
	},{ // Poniżej opis pracownika z tabeli pracownicy
		name : 'pracownik_id',
		type : 'int',
		defaultValue : null
	},{
		name : 'pracownik', //
		type : 'string',
		defaultValue : null
	},{
		name : 'pesel',
		type : 'string',
		defaultValue : null
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

Ext.define('LiderzyModel',{
	extend : 'Ext.data.Model',
	fields : LiderzyFields,
	idProperty : 'id'
});