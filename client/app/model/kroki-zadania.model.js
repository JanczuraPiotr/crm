/**
 * @confirm 2014-09-04 Zmiana znaczenia dat i sposobu notowania wykonywanych kroków.
 */
var KrokiZadaniaFields = [
	{
		name : 'id',
		type : 'int'
	},{
		name : 'nr_zadania',
		type : 'int'
	},{
		name : 'status_zadania_id',
		type : 'int'
	},{
		name : 'data_next_step',
		type : 'date'
	},{
		name : 'notatka',
		type : 'string'
	},{
		name : 'stanowisko_id',
		type : 'int'
	},{
		name : 'stanowisko_symbol',
		type : 'string'
	},{
		name : 'stanowisko_nazwa',
		type : 'string'
	},{
		name : 'data_step',
		type : 'date',
		dateFormat : 'Y-m-d'
	}
];

Ext.define('KrokiZadaniaModel',{
	extend : 'Ext.data.Model',
	xtype : 'kroki-zadania-model',
	alias : 'kroki-zadania-model',
	fields : KrokiZadaniaFields,
	idProperty : 'id'
});