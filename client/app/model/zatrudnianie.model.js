/**
 * @task 4.2.0
 */
var ZatrudnianieField = [
	{
		name : 'id',
		type : 'int'
	},{
		name : 'firma_id',
		type : 'int'
	},{
		name : 'stanowisko_id',
		type : 'int'
	},{
		name : 'pracownik_id',
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
		name : 'data_od',
		type : 'date',
		dateFormat : 'Y-m-d'
	}
];


Ext.define('ZatrudnianieModel',{
	extend : 'Ext.data.Model',
	alias : 'zatrudnianie-model',
	fields : ZatrudnianieField,
	idProperty : 'id'
});
