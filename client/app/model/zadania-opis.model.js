/**
 * @task 4.2.0
 */
var ZadaniaOpisFields = [
	{
		name : 'id',
		type : 'int'
	},{
		name : 'nr_zadania',
		type : 'int'
	},{
		name : 'notatka',
		type : 'string'
	},{
		name : 'create',
		type : 'date',
		dateFormat : 'Y-m-d H:i:s'
	}
];
Ext.define('ZadaniaOpisModel',{
	extend : 'Ext.data.Model',
	fields : ZadaniaOpisFields,
	idProperty : 'id'
});