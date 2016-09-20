/**
 * Przegląd kroków wybranego zadania.
 * Grid wyświetlający kroki zadania którego numer przekazany jest za pomocą zmiennej KrokiZadaniaGrid.setNrZadania(nr_zadania);
 * Kroki zadania wykonane przez inne stanowisko niż to które je przegląda wyświetlane są jako wyszarzałe.
 * Gdy ostatni krok zadania został wykonany przez inne stanowisko pracy niż to które je przegląda blokowane są przyciski do pracy z zadaniem.
 * @task 4.2.0
 * namespace client\app\grid
 */
Ext.define('KrokiZadaniaPreviewGrid',{
	extend : 'Ext.grid.Panel',
	title : 'kroki zadania',


	constructor : function(){
		var def = this;
		def.nr_zadania = -1;
		def.opis_produktu = '';
		def.linked = [];
		def.store = new Ext.create('KrokiZadaniaStore');
		def.StatusyZadaniaStore = new Ext.create('StatusZadaniaStore');

		def.height = 600;
		def.width = 500;

		def.columns = [
			{
				text : 'wykonano',
				dataIndex : 'data_step',
				renderer : function(data){
					var dzien = Ext.util.Format.date(data, 'Y-m-d');
					var godz = Ext.util.Format.date(data, 'H:i');
					return dzien+'<br> godz: '+godz;
				},
				width : 85
			},{
				text : 'notatka',
				dataIndex : 'notatka',
				width : 300
			},{
				text : 'status',
				dataIndex : 'status_zadania_id',
				width : 70,
				renderer : function(value, metaData, record, row, col, store, gridView){
					var rec = def.StatusyZadaniaStore.findRecord('id',value);
					return rec.get('symbol') ;
				}
			}
		];

		def.bbar = [
			{
				xtype: 'pagingtoolbar',
				dock: 'bottom',
				store : def.store,
				pageSize : 30,
				displayMsg : '',
				displayInfo: false
			}
		]; // bbar

		def.plugins = [
			{
				ptype : 'rowexpander',
				rowBodyTpl : [
					'<p>',// @todo wstawić nazwisko i stanowisko osoby wykonującej ten krok
					'</p'
				]
			}
		];

		def.superclass.constructor.call(def,arguments);
	},

	addLinked : function(obiekt){
		var def = this;
		def.linked.push(obiekt);
	},
	setOpisProduktu : function(opis){
		var def = this;
		 def.opis_produktu = opis;
	},
	updateLinked : function(){
		var def = this;
		for (var i in def.linked){
			def.linked[i].setNrZadania(def.nr_zadania);
			def.linked[i].update();
		}
	},
	update : function(){
		var def = this;
		def.store.load();
		def.updateLinked();
	},
	setNrZadania : function(nr_zadania){
		var def = this;
		if(def.nr_zadania !== nr_zadania){
			def.nr_zadania = nr_zadania;
			def.store.setNrZadania(nr_zadania);
			if(def.nr_zadania > 0 ){
				def.enable();
			}else{
				def.setDisabled(true);
			}
		}
	}
});