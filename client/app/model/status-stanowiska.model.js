var StatusStanowiskaFields = [
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
Ext.define('StatusStanowiskaModel',{
	extend : 'Ext.data.Model',
	xtype : 'status-stanowiska-model',
	alias : 'status-stanowiska-model',
	fields : StatusStanowiskaFields,
	idProperty : 'id'
});