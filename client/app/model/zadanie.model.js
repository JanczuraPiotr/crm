/**
 * @confirm 2014-09-04 Zmiana znaczenia dat i sposobu notowania wykonywanych kroków.
 */
var ZadanieFields = [
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
		type : 'date',
		dateFormat : 'Y-m-d H:m:s'
	},{
		name : 'data_step',
		type : 'date',
		dateFormat : 'Y-m-d H:m:s'
	}
];

Ext.define('ZadanieModel',{
	extend : 'Ext.data.Model',
	fields : ZadanieFields,
	idProperty : 'id'
});