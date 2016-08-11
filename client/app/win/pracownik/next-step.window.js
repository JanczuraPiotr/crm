/**
 * @work 2014-09-24 extjs 4.2.2 >> extjs 5.0.1
 */
Ext.define('NextStepWindow',{
	extend : 'Ext.window.Window',
	xtype : 'next-step-window',
	modal : true,
	resizable : false,
	title : 'praca z zadaniem',

	constructor : function(){
		var thisNSW = this;
		thisNSW.nr_zadania = -1;
		thisNSW.recLastStep = null;
		thisNSW.KrokiZadaniaStore = new Ext.create('KrokiZadaniaStore');
		thisNSW.StatusyZadaniaStore = new Ext.create('StatusZadaniaStore').load();
		thisNSW.AktaSprawyPanel = new Ext.create('AktaSprawyPanel',{
			height : 400
		});
		thisNSW.StatusyCombo = new Ext.form.field.ComboBox({
			name : 'status',
			fieldLabel : 'status zadania',
			queryMode : 'remote',
			displayField : 'symbol',
			valueField : 'id',
			store : thisNSW.StatusyZadaniaStore,
			allowBlank : false,
			tpl : Ext.create('Ext.XTemplate',
        '<tpl for=".">',
            '<div class="x-boundlist-item">{symbol} - {opis}</div>',
        '</tpl>'
			)
		});

		Ext.apply(thisNSW,{
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
											minValue : '9:00',
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
										thisNSW.StatusyCombo
									]
								}
							]
						},{
							xtype : 'fieldset',
							margin : 0,
							border : 0,
							padding : 0,
							items : [
								thisNSW.AktaSprawyPanel
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
						var thisWin = btn.up('window');
						var thisForm = btn.up('window').down('form').getForm();
						if(thisForm.isValid()){

							console.log('Treść zapisu kroku');
							console.log(thisForm.getValues()['data_next_step']);
							console.log(thisForm.getValues()['godzina']);
							console.log(thisForm.getValues()['notatka']);
							console.log(thisForm.getValues()['status']);
							var data = {};
							data.nr_zadania = thisNSW.recLastStep.data.nr_zadania;
							data.data_next_step = thisForm.getValues()['data_next_step']+' '+thisForm.getValues()['godzina']+':00';
							data.notatka = thisForm.getValues()['notatka'];
							data.status_zadania_id = thisForm.getValues()['status'];
							Ext.Ajax.request({
								url : '../server/ajax/kroki-zadania.php?action=create&data='+Ext.JSON.encode(data),
								success : function(response){
									var resp = Ext.JSON.decode(response.responseText);
									thisWin.close();
								}
							});


						}
					}
				}
			]
		});

		thisNSW.superclass.constructor.call(thisNSW,arguments);
	},
	setNrZadania : function(nr_zadania){
		var thisNSW = this;
		if(thisNSW.nr_zadania !== nr_zadania){
			thisNSW.nr_zadania = nr_zadania;
			thisNSW.AktaSprawyPanel.setNrZadania(nr_zadania);
		}
	},
	setOpisProduktu : function(opis){
		var thisASP = this;
		thisASP.AktaSprawyPanel.setOpisProduktu(opis);
	},
	lastStep : function(recLastStep){
		var thisNSW = this;
		thisNSW.recLastStep = recLastStep;
		thisNSW.StatusyCombo.setValue(thisNSW.recLastStep.data.status_zadania_id);
	}
});
