/**
 * Przegląd i wykonywanie kroków wybranego zadania dla lidera
 * Grid wyświetlający kroki zadania którego numer przekazany jest za pomocą zmiennej KrokiZadaniaGrid.setNrZadania(nr_zadania);
 * Kroki zadania wykonane przez inne stanowisko niż to które je przegląda wyświetlane są jako wyszarzałe.
 * Gdy ostatni krok zadania został wykonany przez inne stanowisko pracy niż to które je przegląda blokowane są przyciski do pracy z zadaniem.
 * @done 2014-08-28 Rozróżnianie zadań według przynależności do stanowiska
 * @task 4.2.0
 * namesace client\app\grid\leader
 */
Ext.define('KrokiZadaniaGrid',{
	extend : 'Ext.grid.Panel',
	title : 'kroki zadania',
	height : 600,
	width : 500,

	constructor : function(config){
		var def = this;
		def.nr_zadania = -1;
		def.opis_produktu = '';
		def.linked = [];
		def.store = new Ext.create('KrokiZadaniaStore');
		def.store.on('load',def.onStoreLoad,def);
		def.StatusyZadaniaStore = new Ext.create('StatusZadaniaStore');
		def.RowExpander = Ext.create('Ext.grid.plugin.RowExpander',{
			rowBodyTpl : [
				'<p>', // @todo wstawić nazwisko i stanowisko osoby wykonującej ten krok
				'</p'
			]
		});
		def.ButtonNextStep = Ext.create('Ext.Button',{
			text : 'następny krok',
			handler : function(button){
				var nsw = new Ext.create('NextStepWindow');
				nsw.setOpisProduktu(def.opis_produktu);
				nsw.setNrZadania(def.nr_zadania);
				nsw.lastStep(def.store.last());
				nsw.on('close',function(){
						def.update();
					}
				);
				nsw.show();
			}
		});
		def.ButtonTransferToSubordinate = Ext.create('Ext.Button',{
			text : 'przekaż<br>podwładnemu',
			handler : function(button){
				var pzw = new Ext.create('PrzekazZadaniePodwladnemuWindow');
				pzw.setNrZadania(def.nr_zadania);
				pzw.on('close',function(){
					if(pzw.closeStatus === 1){
						def.setNrZadania(0); // Zadanie przeniesiono więc kontrolka powinna przestać wyświetlać informację o nim
						def.update();
					}else if(pzw.closeStatus === -1){
						// Anulowano,
					}
				});
				pzw.show();
			}
		});
		def.ButtonTransferToOtherLeader = Ext.create('Ext.Button',{
			text : 'przekaż<br>liderowi',
			handler : function(button){
				var pzw = new Ext.create('PrzekazZadanieLiderowiWindow');
				pzw.setNrZadania(def.nr_zadania);
				pzw.setLastStep(def.store.last());
				pzw.on('close',function(){
					if(pzw.closeStatus === 1){
						def.setNrZadania(0); // Zadanie przeniesiono więc kontrolka powinna przestać wyświetlać informację o nim
						def.update();
					}else if(pzw.closeStatus === -1){
						// Anulowano,
					}					});
				pzw.show();
			}
		});
		def.BottomBar = Ext.create('Ext.toolbar.Toolbar',{
			items : [
			{
				xtype: 'pagingtoolbar',
				dock: 'bottom',
				store : def.store,
				pageSize : 30,
				displayMsg : '',
				displayInfo: false
			},
			def.ButtonNextStep,
			def.ButtonTransferToSubordinate,
			def.ButtonTransferToOtherLeader
			]
		});

		def.bbar = def.BottomBar;
		def.plugins = [
			def.RowExpander
		];

		def.callParent(arguments);
	},
	initComponent : function(){
		var def = this;
		Ext.apply(def,{
			columns: [
				{
					text : 'data kroku',
					dataIndex : 'data_next_step',
					renderer : function(data){
						var dzien = Ext.util.Format.date(data, 'Y-m-d');
						var godz = Ext.util.Format.date(data, 'H:i');
						return dzien+'<br> godz: '+godz;
					},
					width : 85,
					tdCls : 'tag-td',
					menuDisabled : true,
					sortable : false
				},{
					text : 'notatka',
					dataIndex : 'notatka',
					width : 300,
					tdCls : 'tag-td',
					menuDisabled : true,
					sortable : false
				},{
					text : 'status',
					dataIndex : 'status_zadania_id',
					width : 70,
					renderer : function(value, metaData, record, row, col, store, gridView){
						var rec = def.StatusyZadaniaStore.findRecord('id',value);
						return rec.get('symbol') ;
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
						return 'foregin-step';
					}
				}
			} // viewConfig
		});
		def.callParent();
	},
	addLinked : function(obiekt){
		var def = this;
		def.linked.push(obiekt);
	},
	onStoreLoad : function(store, records, successful, eOpts){
		var def = this;
		var record = records[records.length-1];
		if( record && record.get('stanowisko_id') === CRM.stanowisko_id ){
			def.BottomBar.getComponent(def.ButtonNextStep).setDisabled(false);
//			def.BottomBar.getComponent(def.ButtonTransferToSubordinate).setDisabled(false);
//			def.BottomBar.getComponent(def.ButtonTransferToOtherLeader).setDisabled(false);
		}else{
			def.BottomBar.getComponent(def.ButtonNextStep).setDisabled(true);
//			def.BottomBar.getComponent(def.ButtonTransferToSubordinate).setDisabled(true);
//			def.BottomBar.getComponent(def.ButtonTransferToOtherLeader).setDisabled(true);
		}
	},
	setOpisProduktu : function(opis){
		var thisKZG = this;
		thisKZG.opis_produktu = opis;
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