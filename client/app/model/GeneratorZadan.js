/**
 * @done 4.2.0
 */
var GeneratorZadanFields = [
	{
		name : 'id',
		type : 'int'
	},{
		name : 'zadanie',
		type : 'int'
	},{
		name : 'klient_id',
		type : 'int'
	},{
		name : 'stanowisko_id',
		type : 'int'
	},{
		name : 'produkt_id',
		type : 'int'
	},{
		name : 'status',
		type : 'int'
	},{
		name : 'notatka',
		type : 'string'
	},{
		name : 'data_next_step',
		type : 'date'
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

Ext.define('CRM.model.GeneratorZadan',{
	extend : 'Ext.data.Model',
	fields : GeneratorZadanFields,
	idProperty : 'id'
});