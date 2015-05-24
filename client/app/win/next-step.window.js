/**
 * @prace 2014-09-24 extjs 4.2.2 >> extjs 5.0.1
 */
Ext.define('NextStepWindow',{
	extend : 'Ext.window.Window',
	modal : true,
	resizable : false,
	title : 'praca z zadaniem',

	constructor : function(config){
		var def = this;
		def.nr_zadania = -1;
		def.recLastStep = null;
		def.KrokiZadaniaStore = new Ext.create('KrokiZadaniaStore');
		def.StatusyZadaniaStore = new Ext.create('StatusZadaniaStore').load();
		def.AktaSprawyPanel = new Ext.create('AktaSprawyPanel',{
			height : 400
		});
		def.StatusyCombo = new Ext.form.field.ComboBox({
			name : 'status',
			fieldLabel : 'status zadania',
			queryMode : 'remote',
			displayField : 'symbol',
			valueField : 'id',
			store : def.StatusyZadaniaStore,
			allowBlank : false,
			tpl : Ext.create('Ext.XTemplate',
        '<tpl for=".">',
          '<div class="x-boundlist-item">{symbol} - {opis}</div>',
        '</tpl>'
			)
		});

		def.callParent(arguments);
	},

	initComponent : function(){
		var def = this;

		Ext.apply(def,{
			layout : 'hbox',
			items : [
				{
					xtype : 'form',
					layout : 'hbox',
					defaults : {
						xtype : 'textfield',
						anchor : '100%'
					},
					items : [
						{
							xtype : 'fieldset',
							margin : 10,
							border : 0,
							padding : 0,
							layout : 'hbox',
							items : [
								{
									xtype :'fieldset',
									margin : 0,
									border : 0,
									padding : 0,
									layout : 'vbox',
									items : [
										{
											xtype : 'datefield',
											fieldLabel : 'data kroku',
											name: 'data_next_step',
											allowBlank : false
										},{
											xtype : 'timefield',
											fieldLabel : 'godzina kroku',
											name : 'godzina',
											minValue : '9:00', // @todo Zakres godzin kontaktu przenieść do bazy danych i pozwolić na zarządzanie nimi przez kierowników oddziałów
											maxValue : '18:00',
											format : 'H:i',
											increment : 5,
											allowBlank :false
										},{
											xtype : 'textarea',
											name : 'notatka',
											fieldLabel : 'notatka z kroku',
											width : 400,
											height : 100,
											allowBlank : false
										},
										def.StatusyCombo
									]
								}
							]
						},{
							xtype : 'fieldset',
							margin : 0,
							border : 0,
							padding : 0,
							items : [
								def.AktaSprawyPanel
							]
						}
					]
				}
			],

			buttons : [
				{
					text : 'anuluj',
					handler : function(btn){
						var win = btn.up('window');
						win.close();
					}
				},{
					text : 'wykonaj',
					handler : function(btn){
						// @todo sprawdzić czy w tym miejscu można wykorzystać zmienną def zamiast thisWin
						var thisWin = btn.up('window');
						var thisForm = btn.up('window').down('form').getForm();
						var data = [];
						if(thisForm.isValid()){

							data[0] = {};
							data[0].nr_zadania = def.recLastStep.data.nr_zadania;
							data[0].data_next_step = thisForm.getValues()['data_next_step']+' '+thisForm.getValues()['godzina']+':00';
							data[0].notatka = thisForm.getValues()['notatka'];
							data[0].status_zadania_id = thisForm.getValues()['status'];
							Ext.Ajax.request({
								url : '../server/ajax/kroki-zadania.php?action=create&data='+Ext.JSON.encode(data),
								success : function(response){
									var resp = Ext.JSON.decode(response.responseText);
									if (resp.success === true ){
										console.log('Oznaczono wykonanie następnego kroku');
										console.log(resp);
									} else {
										console.log('Nie udało się wykonać następnego kroku');
										console.log(resp);
									}
									thisWin.close();
								}
							});


						}
					}
				}
			]
		});

		def.callParent();
	},
	onWykonanoKrok : function(zadanie_id){

	},
	setNrZadania : function(nr_zadania){
		var def = this;
		if(def.nr_zadania !== nr_zadania){
			def.nr_zadania = nr_zadania;
			def.AktaSprawyPanel.setNrZadania(nr_zadania);
		}
	},
	setOpisProduktu : function(opis){
		var def = this;
		def.AktaSprawyPanel.setOpisProduktu(opis);
	},
	/**
	 * Informacja o ostatnim kroku w zadaniu
	 */
	lastStep : function(recLastStep){
		var def = this;
		def.recLastStep = recLastStep;
		def.StatusyCombo.setValue(def.recLastStep.data.status_zadania_id);
	}
});
