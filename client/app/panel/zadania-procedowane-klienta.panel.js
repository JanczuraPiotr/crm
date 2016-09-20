/**
 * Zespół kontrolek do pracy z zadaniami jednego klienta.
 * Dane klienta należy podać jako obiekt Model przekazany w obiekcie konfiguracyjnym pod zmienną recKlient
 * @done 2014-09-04 Zmiana znaczenia dat i sposobu notowania wykonywanych kroków.
 * @task 4.2.0
 */
Ext.define('ZadaniaProcedowaneKlientaPanel',{
	extend : 'Ext.panel.Panel',
	width : 1200,
	height : 600,

	constructor : function(config){
		var def = this;
		def.AktaSprawyPanel = new Ext.create('AktaSprawyPanel').setDisabled(true);
		def.KrokiZadaniaGrid = new Ext.create('KrokiZadaniaGrid').setDisabled(true);

		def.NaglowkiZadanGrid = new Ext.create('NaglowkiZadanProcedowanychKlientaGrid',{
			listeners : {
				select : function( thet, record, index, eOpts ){
					def.AktaSprawyPanel.setNrZadania(record.data.nr_zadania);
					def.AktaSprawyPanel.setOpisProduktu(record.data.produkt_opis);
					def.KrokiZadaniaGrid.setNrZadania(record.data.nr_zadania);
					def.KrokiZadaniaGrid.setOpisProduktu(record.data.produkt_opis);
				},
				deselect : function( thet, record, index, eOpts ){
					def.AktaSprawyPanel.setNrZadania(0);
					def.AktaSprawyPanel.setOpisProduktu('');
					def.KrokiZadaniaGrid.setNrZadania(0);
					def.KrokiZadaniaGrid.setOpisProduktu('');
				}
			},
			recKlient : config.recKlient
		});

		// @done 2014-08-22
		// @todo 1 Dodać możliwość wyboru czy stanowisko będzie obserwować zadania wszystkie czy tylko te za które odpowiada lub dodać informację do każdego zadania kto za nie odpowiada.
		// Skoro zadania są przeglądane z perspektywy klienta to każdy pracownik powinien mieć możliwość obejżenia wszystkich zadań nie zależnie od tego czy jest odpowidzialny za to zadanie.
		// def.setStanowiskoId(CRM.stanowisko_id);
		def.KrokiZadaniaGrid.addLinked(def.AktaSprawyPanel);
		def.KrokiZadaniaGrid.addLinked(def.NaglowkiZadanGrid);

		def.callParent(arguments);
	},

	initComponent : function(){
		var def = this;
		Ext.apply(def,{
			layout : 'hbox',
			items : [
				def.NaglowkiZadanGrid,
				def.KrokiZadaniaGrid,
				def.AktaSprawyPanel
			]
		});
		def.callParent();
	},

	setStanowiskoId : function(stanowisko_id){
		var def = this;
		if(def.stanowisko_id !== stanowisko_id){
			def.stanowisko_id = stanowisko_id;
			def.NaglowkiZadanGrid.getStore().remoteFilter = true;
			def.NaglowkiZadanGrid.getStore().filter({
				property : 'stanowisko_id',
				value    : def.stanowisko_id,
				operator : '='
			});
			def.NaglowkiZadanGrid.getStore().load();
			if(def.stanowisko_id < 1){
				def.AktaSprawyPanel.setNrZadania(0);
				def.AktaSprawyPanel.setOpisProduktu('');
				def.KrokiZadaniaGrid.setNrZadania(0);
				def.KrokiZadaniaGrid.setOpisProduktu('');
				def.NaglowkiZadanGrid.setDisabled(true);
			}else{
				def.NaglowkiZadanGrid.enable();
			}
		}
	}
});