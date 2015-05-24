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

Ext.define('StatusKlientaModel',{
	extend : 'Ext.data.Model',
	alias : 'status-klienta-model',
	idProperty : 'id',
	fields : StatusKlientaFields
});