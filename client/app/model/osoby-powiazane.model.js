/**
 * @task 4.2.0
 */
var OsobyPowiazaneFields = [
	{
		name : 'id',
		type : 'int'
	},{
		name : 'klient_id',
		type : 'int'
	},{
		name : 'nazwa',
		type : 'string'
	},{
		name : 'imie',
		type : 'string'
	},{
		name : 'pesel',
		type : 'string'
	},{
		name : 'nip',
		type : 'string'
	},{
		name : 'kod_poczt',
		type : 'string'
	},{
		name : 'miejscowosc',
		type : 'string'
	},{
		name : 'ul',
		type : 'string'
	},{
		name : 'nr_b',
		type : 'string'
	},{
		name : 'nr_l',
		type : 'string'
	},{
		name : 'email',
		type : 'string'
	},{
		name : 'telkom',
		type : 'string'
	},{
		name : 'teldom',
		type : 'string'
	},{
		name : 'telpraca',
		type : 'string'
	}
];

Ext.define('OsobyPowiazaneModel',{
	extend : 'Ext.data.Model',
	fields : OsobyPowiazaneFields,
	idProtperty : 'id'
});