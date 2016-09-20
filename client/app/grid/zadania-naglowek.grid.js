/**
 * @done 2014-12-16
 * @task 4.2.0
 */
Ext.define('ZadaniaNaglowekGrid',{
	extend : 'Ext.grid.Panel',
	xtype : 'zadania-naglowek-grid',
	autoLoad : false,
	autoSync : true,
	autoSave : false,
	idProperty : 'id',

	constructor : function(){
		var def = this;
		def.height = 600;
		def.width = 415;

		def.ZadaniaNaglowekStore = new Ext.create('ZadaniaNaglowekStore');

		Ext.apply(def,{
				title : 'naglówki zadań',
				store : def.ZadaniaNaglowekStore,
				columns:[
					{
						text : 'data kroku',
						dataIndex : 'data_next_step',
						width : 70,
						renderer : Ext.util.Format.dateRenderer('Y-m-d')
					},{
						text : 'produkt',
						width : 150,
						renderer : function(value, metaData, record, row, col, store, gridView){
							return	'produkt : <b>'+record.data.produkt_nazwa+'</b><br>'+
											'bank    : <i>'+record.data.bank_nazwa+'</i>'
							;
						}
					},{
						text : 'klient',
						width : 150,
						renderer : function(value, metaData, record, row, col, store, gridView){
							return	'<b>'+record.data.klient_nazwa+'</b><br>'+
											(record.data.unique_typ != null ? record.data.unique_typ+' : '+record.data.unique_value : '')+'</br>'+
											record.data.kod_poczt+' '+record.data.miejscowosc+'</br>'+
											record.data.ul+' '+record.data.nr_b+'/'+record.data.nr_l
							;
						}
					}
				], // columns
				bbar : [
					{
						xtype: 'pagingtoolbar',
						dock: 'bottom',
						store : def.ZadaniaNaglowekStore,
						pageSize : 30,
						displayMsg : '',
						displayInfo: false
					}
				], // bbar
				plugins : [
					{
						ptype : 'rowexpander',
						rowBodyTpl : [
							'<hr>',
							'<p>',
								'nazwa klienta : <b>{klient_nazwa}</b><br>',
								'{unique_typ} : <b>{unique_value}</b><br>',
								'miejscowosc : <b>{miejscowosc}</b><br>',
								'adres : <b>{ul} {nr_b}/{nr_l}</b><br>',
								'tel kom : <b>{telkom}</b><br>',
								'tel domowy : <b>{teldom}</b><br>',
								'tel praca : <b>{telpraca}</b><br>',
							'</p>'
						]
					}
				]
		});

		def.superclass.constructor.call(def,arguments);
	},

	przypisanoZadania : function(stanowisko_id, zadania){
		this.getStore().load();
	}
});
