/**
 * Przekazuje zadanie do podwładnego.
 * @task 4.2.0
 */
Ext.define('PrzekazZadaniePodwladnemuWindow',{
	extend : 'Ext.window.Window',
	xtype : 'przekaz-zadanie-podwladnemu-window',
	modal : true,
	resizable : false,
	title : 'Przekazywanie zadania podwladnemu',

//	constructor : function(){
	initComponent : function(){
		var thisPZPW = this;
		thisPZPW.closeStatus = 0; // 1 : zamknięto po poprawnym przeniesieniu, -1 : anulowano, -2 : nie udana próba przeniesienia
		thisPZPW.nr_zadania = -1; // numer przesyłanego zadania
		thisPZPW.stanowisko_id = -1; // stanowisko do którego przesyłane jest zadanie
		thisPZPW.KrokiZadaniaStore = new Ext.create('KrokiZadaniaStore');
		thisPZPW.StanowiskaZespoluGrid = new Ext.create('StanowiskaZespoluGrid');
		thisPZPW.StanowiskaZespoluGrid.enable();
		thisPZPW.StanowiskaZespoluGrid.on('select', function( thiss, record, index, eOpts ){
			thisPZPW.stanowisko_id = record.data.stanowisko_id;
		});
		thisPZPW.StanowiskaZespoluGrid.on('deselect', function( thiss, record, index, eOpts ){
			thisPZPW.stanowisko_id = 0;
		});


		Ext.apply(thisPZPW,{
			layout : 'hbox',
			items : [
				{
					xtype : 'form',
					layout : 'hbox',
					padding : 0,
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
									width : 300,
									height : 100,
									allowBlank : false
								},
								thisPZPW.StanowiskaZespoluGrid
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
						win.closeStatus = -1;
						win.close();
					}
				},{
					text : 'wykonaj',
					handler : function(btn){
						var thisWin = btn.up('window');
						var thisForm = btn.up('window').down('form').getForm();
						if(thisForm.isValid()){
							if(thisPZPW.stanowisko_id < 1){
								Ext.Msg.alert('Błąd !','Aby przekazać zadanie do pracownika musisz wskazać odpowiedni wiersz w tabeli z podległymi stanowiskami');
							}else{
								var data = {};
								data.nr_zadania = thisPZPW.nr_zadania;
								data.notatka = thisForm.getValues()['notatka'];
								data.stanowisko_id = thisPZPW.stanowisko_id;
								Ext.Ajax.request({
									url : '../server/ajax/kroki-zadania.php?action=do-podwladnego&data='+Ext.JSON.encode(data),
									success : function(response){
										var resp = Ext.JSON.decode(response.responseText);
										thisWin.closeStatus = 1;
										thisWin.close();
									}
								});
							}
						}
					}
				}
			]
		});

//		thisPZPW.superclass.constructor.call(thisPZPW,arguments);
		thisPZPW.callParent();
	},
	setNrZadania : function(nr_zadania){
		var thisNSW = this;
		if(thisNSW.nr_zadania !== nr_zadania){
			thisNSW.nr_zadania = nr_zadania;
		}
	}
});