/**
 * @task 4.2.0
 */
var StanowiskaField = [
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
		name : 'placowka_id',
		type : 'int'
	},{
		name : 'pracownik_id',
		type : 'int'
	},{
		name : 'tel',
		type : 'string'
	},{
		name : 'email',
		type : 'string'
	},{
		name : 'status_stanowiska_id',
		type : 'int'
	},{
		name : 'data_od',
		type : 'date',
		dateFormat : 'Y-m-d'
	},{
		name : 'data_do',
		type : 'date',
		dateFormat : 'Y-m-d'
	},{
		name : 'pracownik',
		type : 'string'
	},{
		name : 'pesel',
		type : 'string'
	}
];

Ext.define('StanowiskaModel',{
	extend : 'Ext.data.Model',
	fields : StanowiskaField,
	idProperty : 'id'
});