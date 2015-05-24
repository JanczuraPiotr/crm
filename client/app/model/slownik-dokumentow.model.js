var SlownikDokumentowFields = [
	{
		name : 'id',
		type : 'int'
	},{
		name : 'symbol',
		type : 'string'
	},{
		name : 'nazwa',
		type : 'string'
	}
];
Ext.define('SlownikDokumentowModel',{
	extend : 'Ext.data.Model',
	xtype : 'slownik-dokumentow-model',
	alias : 'slownik-dokumentow-model',
	fields : SlownikDokumentowFields,
	idProperty : 'id'
});