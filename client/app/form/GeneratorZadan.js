/**
 * @work 4.2.0
 */
Ext.define('CRM.form.GeneratorZadan',{
	extend : 'Ext.form.Panel',
	title : 'Generator Zadań',
	requires : [
		'CRM.grid.GeneratorZadan',
		'CRM.model.GeneratorZadan'
	],
	constructor : function(){
		var def = this;
		def.index = -1; // index rekordu umieszczonego w formularzy
		def.produktId = -1;
		def.firmaId = CRM.firma_id;


		def.configContainer = new Ext.create('Ext.container.Container',{
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
										produkt_id : def.produktId,
										firma_id : def.firmaId
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

		def.gneratorZadanGrid = new Ext.create('CRM.grid.GeneratorZadan',{
			height : 574,
			width : 360,
			listeners : {
				select : function( grid, record, index, eOpts ){
					def.produktId = record.data.id;
					def.setProduktId(def.produktId, index);
					def.getForm().loadRecord(record);
					def.configContainer.enable();

				},
				deselect : function( grid, record, index, eOpts ){
					def.produktId = 0;
					def.setProduktId(def.produktId);
					def.configContainer.setDisabled(true);
				}
			},
			setBankId : function(bankId){
				def.configContainer.setDisabled(true);
				def.produktId = 0;

				def.gneratorZadanGrid.produktyStore.setBankId(bankId);
				def.gneratorZadanGrid.bank_id = bankId;
				if(bankId > 0){
					def.gneratorZadanGrid.enable();
				}else{
					def.gneratorZadanGrid.setDisabled(true);
				}
			}
		});
		def.store = def.gneratorZadanGrid.getStore(),

		def.gneratorZadanGrid.getStore().onWriteCreate = function(store,operation,eOpts){console.log('GeneratorZadanForm::onWriteCreate');
		},

		def.callParent(arguments);
//		def.superclass.constructor.call(def,arguments);
	},

	initComponent : function(){console.log('CRM.grid.GeneratorZadan::initComponent()');
		var def = this;

		Ext.apply(def,{
			frame : true,
			padding : 0,

			layout : 'hbox',
			width : 800,
			height : 600,
			items : [
				def.gneratorZadanGrid,
				def.configContainer
			]
		});

		def.callParent();
	},

	setFirmaId : function(firmaId){
		var def = this;
		def.firmaId = firmaId;
	},
	setNazwaBanku : function(nazwa){
		var def = this;
		def.setNazwa(nazwa);
	},
	setNazwa : function(nazwa){
		var def = this;
		def.setTitle('Produkty banku : '+nazwa);
	},
	setBankId : function(bank_id){
		var def = this;
		def.gneratorZadanGrid.setBankId(bank_id);
		def.loadRecord(Ext.create('CRM.model.GeneratorZadan',{bank_id:'' ,symbol:'',nazwa:'',opis:'',data_od:'',data_do:''}));
		def.index = -1;
		def.produktId = -1;
	},
	setProduktId : function(produktId,index){
		var def = this;
		def.produktId = produktId;
		def.index = index;
	}
});