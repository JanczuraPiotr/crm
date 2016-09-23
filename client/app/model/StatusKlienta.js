/**
 * @work 4.2.0
 */
var StatusKlientaFields = [
	{
		name : 'id',
		type : 'int'
	},{
		name : 'symbol',
		type : 'string'
	},{
		name : 'status',
		type : 'int'
	},{
		name : 'opis',
		type : 'string'
	}
];

Ext.define('CRM.model.StatusKlienta',{
	extend : 'Ext.data.Model',
	alias : 'status-klienta-model',
	idProperty : 'id',
	fields : StatusKlientaFields
});