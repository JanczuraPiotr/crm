/**
 * @prace 2014-09-24 extjs 4.2.2 >> extjs 5.0.1
 */
Ext.define('PrzekazZadanieLiderowiWindow',{
	extend : 'Ext.window.Window',
	xtype : 'przekaz-zadanie-liderowi-window',
	modal : true,
	resizable : false,
	title : 'Przekazywanie zadania podwladnemu',

	constructor : function(){
		var thisPZLW = this;
		thisPZLW.closeStatus = 0; // 1 : zamknięto po poprawnym przeniesieniu, -1 : anulowano, -2 : nie udana próba przeniesienia
		thisPZLW.nr_zadania = -1; // numer przesyłanego zadania
		thisPZLW.stanowisko_id = -1; // stanowisko do którego przesyłane jest zadanie
		thisPZLW.KrokiZadaniaStore = new Ext.create('KrokiZadaniaStore');
		thisPZLW.StanowiskaLiderowGrid = new Ext.create('StanowiskaLiderowKooperantowGrid');
		thisPZLW.StanowiskaLiderowGrid.on('select', function( thiss, record, index, eOpts ){
			thisPZLW.stanowisko_id = record.data.id;
		});
		thisPZLW.StanowiskaLiderowGrid.on('deselect', function( thiss, record, index, eOpts ){
			thisPZLW.stanowisko_id = 0;
		});

		Ext.apply(thisPZLW,{
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
									labelAlign : 'top',
									width : 400,
									height : 100,
									allowBlank : false
								},
								thisPZLW.StanowiskaLiderowGrid
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
						thisPZLW.closeStatus = -1;
						win.close();
					}
				},{
					text : 'wykonaj',
					handler : function(btn){
						var thisWin = btn.up('window');
						var thisForm = btn.up('window').down('form').getForm();
						if(thisForm.isValid()){

							var data = {};
							data.nr_zadania = thisPZLW.recLastStep.data.nr_zadania;
							data.notatka = thisForm.getValues()['notatka'];
							data.stanowisko_id = thisPZLW.stanowisko_id;
							Ext.Ajax.request({
								url : '../server/ajax/kroki-zadania.php?action=do-lidera-kooperanta&data='+Ext.JSON.encode(data),
								success : function(response){
									var resp = Ext.JSON.decode(response.responseText);
									thisPZLW.closeStatus = 1;
									thisWin.close();
								}
							});


						}
					}
				}
			]
		});

		thisPZLW.superclass.constructor.call(thisPZLW,arguments);
	},
	setNrZadania : function(nr_zadania){
		var thisNSW = this;
		if(thisNSW.nr_zadania !== nr_zadania){
			thisNSW.nr_zadania = nr_zadania;
		}
	},
	/**
	 * Informacja o ostatnim kroku w zadaniu
	 */
	setLastStep : function(recLastStep){
		var thisNSW = this;
		thisNSW.recLastStep = recLastStep;
	}
});