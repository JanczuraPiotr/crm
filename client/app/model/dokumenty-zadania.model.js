var DokumentyZadaniaFields = [
	{
		name : 'id',
		type : 'int'
	},{
		name : 'slownik_id',
		type : 'int'
	},{
		name : 'slownik_symbol',
		type : 'string'
	},{
		name : 'slownik_nazwa',
		type : 'string'
	},{
		name : 'nr_zadania',
		type : 'int'
	},{
		name : 'adnotacje',
		type : 'string'
	},{
		name : 'data_dostarczenia',
		type : 'date',
		dateFormat : 'Y-m-d'
	}
];
Ext.define('DokumentyZadaniaModel',{
	extend : 'Ext.data.Model',
	fields : DokumentyZadaniaFields,
	idProperty : 'id'
});