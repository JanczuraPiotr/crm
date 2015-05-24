var StanowiskoPracownikFields = [
	{
		name : 'id',
		type : 'int'
	},{
		name : 'stanowisko_id',
		type : 'int',
		defaultValue : null
	},{
		name : 'stanowisko_symbol',
		type : 'string',
		defaultValue : null
	},{
		name : 'stanowisko_nazwa',
		type : 'string',
		defaultValue : null
	},{
		name : 'stanowisko_tel',
		type : 'string',
		defaultValue : null
	},{
		name : 'pracownik_id',
		type : 'int',
		defaultValue : null
	},{
		name : 'pracownik_nazwisko',
		type : 'string',
		defaultValue : null
	},{
		name : 'pracownik_imie',
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
Ext.define('StanowiskoPracownikModel',{
	extend : 'Ext.data.Model',
	fields : StanowiskoPracownikFields,
	idProperty : 'id'
});