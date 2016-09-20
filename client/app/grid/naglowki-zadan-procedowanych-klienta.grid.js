/**
 * Nagłówki zadań w trakcie procedowania dla wskazanego klienta.
 * Podobna klasa : ZadaniaNaglowekMiniGrid wyświetla również informacje o kliencie wobec którego wykonywane jest zadanie.
 * @done 2014-09-04 Zmiana znaczenia dat i sposobu notowania wykonywanych kroków.
 * @done 2014-08-28
 * @task 4.2.0
 * @todo Do grida z nagłówkami zadań dodać menu lokalne w którym wywoływać okno z zadaniami w trakcie dla klienta na którym wywołano menu lokalne
 * namespace client\app\grid
 * use client\app\store
 */
Ext.define('NaglowkiZadanProcedowanychKlientaGrid',{
	extend : 'Ext.grid.Panel',
	autoLoad : false,
	autoSync : true,
	autoSave : false,
	idProperty : 'id',
	height : 600,
	width : 300,

	/**
	 * @param {object} config elementem obiektu musi być obiekt model opisujący jednego klienta. Dla tego klienta tworzony jest grid.
	 */
	constructor : function(config){
		var def = this;
		var recKlient = config && ( config.recKlient || null);
		var confStore = {};
		if(recKlient){
			confStore.recKlient = recKlient;
		};
		//def.RowsFilter = new RowsFilter(def);
		def.ZadaniaNaglowekStore = new Ext.create('ZadaniaNaglowekStore',confStore);
		def.callParent(arguments);
	},

	initComponent : function(){
		var def = this;
		Ext.apply(def,{
				title : 'naglówki zadań',
				store : def.ZadaniaNaglowekStore,
				columns:[
					{
						text : 'nr',
						dataIndex : 'nr_zadania',
						width : 50,
						tdCls : 'tag-td',
						menuDisabled : true,
						sortable : false
					},{
						text : 'termin',
						dataIndex : 'data_next_step',
						renderer : function(data){
							var dzien = Ext.util.Format.date(data, 'Y-m-d');
							var godz = Ext.util.Format.date(data, 'H:i');
							return dzien+'<br> godz: '+godz;
						},
						width : 70,
						tdCls : 'tag-td',
						menuDisabled : true,
						sortable : false
					},{
						text : 'produkt',
						width : 150,
						renderer : function(value, metaData, record, row, col, store, gridView){
							return	'<b>'+record.data.produkt_nazwa+'</b><br>'+
											'bank    : <i>'+record.data.bank_nazwa+'</i>'
							;
						},
						tdCls : 'tag-td',
						menuDisabled : true,
						sortable : false
					}
				], // columns
				viewConfig : {
					stripeRows : false,
					getRowClass : function(record, index){
						if(record.get('stanowisko_id') !== CRM.stanowisko_id ){
							return 'foregin-task';
						}
					}
				},
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
		def.callParent();
	},
	setNrZadania : function(nr_zadania){
		var def = this;
		def.nr_zadania = nr_zadania;
		def.ZdaniaNaglowekStore.setNrZadania(nr_zadania);
	},
	update : function(){
		var def = this;
		def.ZadaniaNaglowekStore.load();
	},
	przypisanoZadania : function(){
		this.getStore().load();
	}
});
