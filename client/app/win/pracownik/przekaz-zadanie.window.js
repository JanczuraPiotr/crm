/**
 * Przekazuje zadanie od pracownika szeregowego do jego lidera.
 * @task 4.2.0
 */
Ext.define('PrzekazZadanieWindow',{
	extend : 'Ext.window.Window',
	xtype : 'przekaz-zadanie-window',
	modal : true,
	resizable : false,
	title : 'Przekazywanie zadania liderowi',

	constructor : function(){
		var def = this;
		def.nr_zadania = -1;
		def.closeStatus = 0; // 1 : zamknięto po poprawnym przeniesieniu, -1 : anulowano, -2 : nie udana próba przeniesienia
		def.recLastStep = null;
		def.KrokiZadaniaStore = new Ext.create('KrokiZadaniaStore');


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
									xtype : 'textarea',
									name : 'notatka',
									fieldLabel : 'notatka',
									width : 400,
									height : 100,
									allowBlank : false
								}
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
						def.closeStatus = -1;
						win.close();
					}
				},{
					text : 'wykonaj',
					handler : function(btn){
						var thisWin = btn.up('window');
						var thisForm = btn.up('window').down('form').getForm();
						if(thisForm.isValid()){

							var data = {};
							data.nr_zadania = def.recLastStep.data.nr_zadania;
							data.notatka = thisForm.getValues()['notatka'];
							Ext.Ajax.request({
								url : '../server/ajax/kroki-zadania.php?action=do-lidera&data='+Ext.JSON.encode(data),
								success : function(response){
									var resp = Ext.JSON.decode(response.responseText);
									def.closeStatus = 1;
									thisWin.close();
								}
							});


						}
					}
				}
			]
		});

		def.superclass.constructor.call(def,arguments);
	},
	setNrZadania : function(nr_zadania){
		var def = this;
		if(def.nr_zadania !== nr_zadania){
			def.nr_zadania = nr_zadania;
		}
	},
	/**
	 * Informacja o ostatnim kroku w zadaniu
	 */
	setLastStep : function(recLastStep){
		var def = this;
		def.recLastStep = recLastStep;
	}
});