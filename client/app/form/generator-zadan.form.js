/**
 * @confirm 2015-01-02 ExtJS 5.1.0
 */
Ext.define('GeneratorZadanForm',{
	extend : 'Ext.form.Panel',
	xtype : 'generator-zadan-form',
	title : 'Generator Zadań',

	constructor : function(){
		var def = this;
		def.index = -1; // index rekordu umieszczonego w formularzy
		def.produkt_id = -1;
		def.firma_id = CRM.firma_id;


		def.Container = new Ext.create('Ext.container.Container',{
			disabled : true,
			layout: 'vbox',
			items : [
				{
					items : [
						{
							xtype : 'fieldset',
							title : 'Miejsce na konfigurację spodobu generowania zadań',
							items : [
								{
									xtype : 'displayfield',
									width : 400,
									value : 'Obecnie zadanie tworzone jest dla klienta należącego do firmy gdy firma połączona jest z bankiem oferującym produkt i gdy klient nie miał jeszcze proponowanego tego produktu'
								}
							]
						},{
							xtype : 'button',
							text : 'generuj',
							handler : function(p1,p2){
								Ext.Ajax.request({
									url : '../server/ajax/generator-zadan.php?action=generate',
									params : {
										produkt_id : def.produkt_id,
										firma_id : def.firma_id
									},
									success : function(response){
										var resp = Ext.JSON.decode(response.responseText);
										if(resp.data['count'] > 0 ){
											Ext.Msg.alert('Utworzono zadania','Ilość utworzonych zadań : '+resp.data['count']);
										}else{
											Ext.Msg.alert('Nie utworzono zadania','Nie udało się wygenerować zadań dla podanych kryteriów');
										}
									}
								});
							}
						}
					]
				}
			]
		});

		def.GeneratorZadanGrid = new Ext.create('GeneratorZadanGrid',{
			height : 574,
			width : 360,
			listeners : {
				select : function( thiss, record, index, eOpts ){
					def.produkt_id = record.data.id;
					def.setProduktId(def.produkt_id, index);
					def.getForm().loadRecord(record);
					def.Container.enable();

				},
				deselect : function( thiss, record, index, eOpts ){
					def.produkt_id = 0;
					def.setProduktId(def.produkt_id);
					def.Container.setDisabled(true);
				}
			},
			setBankId : function(bank_id){
				def.Container.setDisabled(true);
				def.produkt_id = 0;

				def.GeneratorZadanGrid.ProduktyStore.setBankId(bank_id);
				def.GeneratorZadanGrid.bank_id = bank_id;
				if(bank_id > 0){
					def.GeneratorZadanGrid.enable();
				}else{
					def.GeneratorZadanGrid.setDisabled(true);
				}
			}
		});
		def.store = def.GeneratorZadanGrid.getStore(),

		def.GeneratorZadanGrid.getStore().onWriteCreate = function(store,operation,eOpts){
			console.log('GeneratorZadanForm::onWriteCreate');
		},

		def.callParent(arguments);
//		def.superclass.constructor.call(def,arguments);
	},

	initComponent : function(){
		console.log('GeneratorZadanForm::initComponent()');
		var def = this;
		Ext.apply(def,{
			frame : true,
			padding : 0,

			layout : 'hbox',
			width : 800,
			height : 600,
			items : [
				def.GeneratorZadanGrid,
				def.Container
			]
		});
		def.callParent();
	},

	setFirmaId : function(firma_id){
		var def = this;
		def.firma_id = firma_id;
	},
	setNazwaBanku : function(nazwa){
		var def = this;
		def.setNazwa(nazwa);
	},
	setNazwa : function(nazwa){
		var def = this;
		def.setTitle('Prodykty banku : '+nazwa);
	},
	setBankId : function(bank_id){
		var def = this;
		def.GeneratorZadanGrid.setBankId(bank_id);
		def.loadRecord(new GeneratorZadanModel({bank_id:'' ,symbol:'',nazwa:'',opis:'',data_od:'',data_do:''}));
		def.index = -1;
		def.produkt_id = -1;
	},
	setProduktId : function(produkt_id,index){
		var def = this;
		def.produkt_id = produkt_id;
		def.index = index;
	}
});