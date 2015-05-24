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

Ext.define('GeneratorZadanModel',{
	extend : 'Ext.data.Model',
	xtype : 'generator-zadan-model',
	alias : 'generator-zadan-model',
	fields : GeneratorZadanFields,
	idProperty : 'id'
});