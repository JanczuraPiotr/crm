/**
 * @task 4.2.0
 */
var PochodzenieKlientowFields = [
	{
		name : 'id',
		type : 'int'
	},{
		name : 'symbol',
		type : 'string'
	},{
		name : 'opis',
		type : 'string'
	}
];

Ext.define('CRM.model.PochodzenieKlientow',{
	extend : 'Ext.data.Model',
	fields : PochodzenieKlientowFields,
	idProperty : 'id'
});