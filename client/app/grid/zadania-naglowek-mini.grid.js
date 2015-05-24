/**
 * @confirm 2015-01-02 ExtJS 5.1.0
 * @todo Do grida z nagłówkami zadań dodać menu lokalne, w którym wywoływać okno z zadaniami w trakcie, dla klienta, na którym wywołano menu lokalne
 * namespace client\view
 * use client\store\ZadaniaNaglowekSore
 */
Ext.define('ZadaniaNaglowekMiniGrid',{
	extend : 'Ext.grid.Panel',
	width : 350,
	height : 600,

	constructor : function(){
		var def = this;
		def.nr_zadania = 0;
		def.stanowisko_id = 0;

		def.callParent(arguments);
	},
	initComponent : function(){
		var def = this;
		def.ZadaniaNaglowekStore = new Ext.create('ZadaniaNaglowekStore');

		Ext.apply(def,{
			title : 'naglówki zadań',
			store : def.ZadaniaNaglowekStore,
			columns:[
				{
					text : 'nr',
					dataIndex : 'nr_zadania',
					width : 40
				},{
					text : 'termin',
					dataIndex : 'data_next_step',
					renderer : function(data){
						var dzien = Ext.util.Format.date(data, 'Y-m-d');
						var godz = Ext.util.Format.date(data, 'H:i');
						return dzien+'<br> godz: '+godz;
					},
					width : 70
				},{
					text : 'klient',
					width : 120,
					renderer : function(value, metaData, record, row, col, store, gridView){
						var r = record.data;
						return	'<b>'+
										r.klient_nazwisko+'<br/>'+
										r.klient_imie+
										'</b><hr>'+
										r.miejscowosc+'<br>'+
										r.ul+' '+r.nr_b+ ( r.nr_l.length > 0 ? '/'+r.nr_l : '')+
										( r.telkom.length > 0 ? '<br> tel kom: '+r.telkom : '')+
										( r.teldom.length > 0 ? '<br> tel dom: '+r.teldom : '')+
										( r.telpraca.length > 0 ? '<br> tel praca: '+r.telpraca : '');
					}
				},{
					text : 'produkt',
					width : 100,
					renderer : function(value, metaData, record, row, col, store, gridView){
						return	'<b>'+record.data.produkt_nazwa+'</b><br>'+
										'<i>'+record.data.bank_nazwa+'</i>'
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
			] // bbar
		});

		def.callParent(arguments);
	},
	setNrZadania : function(nr_zadania){
		var def = this;
		def.nr_zadania = nr_zadania;
		def.ZadaniaNaglowekStore.setNrZadania(nr_zadania);
	},
	setStanowiskoId : function(stanowisko_id){
		var def = this;
		def.stanowisko_id = stanowisko_id;
		def.ZadaniaNaglowekStore.setStanowiskoId(stanowisko_id);
	},
	update : function(){
		var def = this;
		def.ZadaniaNaglowekStore.load();
	}
});
