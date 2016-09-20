/**
 * @task 4.2.0
 */
var ZadaniaNaglowekFields = [
	{
		name : 'id',
		type : 'int'
	},{
		name : 'nr_zadania',
		type : 'int'
	},{
		name : 'stanowisko_id',
		type : 'int'
	},{
		name : 'klient_id',
		type : 'int'
	},{
		name : 'klient_nazwa', // Nazwa + imie
		type : 'string'
	},{
		name : 'klient_nazwisko',
		type : 'string'
	},{
		name : 'klient_imie',
		type : 'string'
	},{
		name : 'unique_typ', // pesel albo nip
		type : 'string'
	},{
		name : 'unique_value',
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
		name : 'telkom',
		type : 'string'
	},{
		name : 'teldom',
		type : 'string'
	},{
		name : 'telpraca',
		type : 'string'
	},{
		name : 'email',
		type : 'string'
	},{
		name : 'produkt_id',
		type : 'int'
	},{
		name : 'produkt_symbol',
		type : 'string'
	},{
		name : 'produkt_nazwa',
		type : 'string'
	},{
		name : 'produkt_opis',
		type : 'string'
	},{
		name : 'bank_id',
		type : 'string'
	},{
		name : 'bank_symbol',
		type : 'string'
	},{
		name : 'bank_nazwa',
		type : 'string'
	},{
		name : 'data_next_step',
//		type : 'string'
		type : 'date',
		dateFormat : 'Y-m-d'
	}
];

Ext.define('ZadaniaNaglowekModel',{
	extend : 'Ext.data.Model',
	fields : ZadaniaNaglowekFields,
	idProperty : 'id'
});
