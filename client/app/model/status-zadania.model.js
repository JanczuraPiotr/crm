var StatusZadaniaFields = [
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

Ext.define('StatusZadaniaModel',{
	extend : 'Ext.data.Model',
	alias : 'status-zadania-model',
	idProperty : 'id',
	fields : StatusZadaniaFields
});