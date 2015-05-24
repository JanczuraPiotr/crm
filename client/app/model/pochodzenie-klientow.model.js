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

Ext.define('PochodzenieKlientowModel',{
	extend : 'Ext.data.Model',
	fields : PochodzenieKlientowFields,
	idProperty : 'id'
});