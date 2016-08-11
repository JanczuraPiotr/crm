/**
 * @work 2014-09-24 extjs 4.2.2 >> extjs 5.0.1
 */
Ext.define('WyborDatyWindow',{
	extend : 'Ext.window.Window',
	title : 'Wybierz datę',
	autoShow : false,
	modal : true,
	layout: {
			type: 'fit'
	},
	items : [
		{
			xtype : 'form',
			bodyPadding: 15,
			defaults: {
				xtype: 'textfield',
				anchor: '100%',
				labelWidth: 60
			},
			items : [
				{
					xtype : 'datefield',
					text : 'data',
					name : 'data',
					fieldLabel : 'data',
					validateBlank : true,
					validateOnBlur : true,
					allowBlank : false,
					blankText : 'Musisz podać datę',
					format : 'Y-m-d'
				}
			]
		}
	],
	dockedItems : [
		{
			xtype : 'toolbar',
			dock : 'bottom',
			items: [
				{
					xtype : 'button',
					text : 'wybieram',
					itemId : 'submit',
					formBind : true,
					handler : function(btn){
						var thisForm = btn.up('window').down('form').getForm();
						if(thisForm.isValid()){
							btn.up('window').onWybranoDate(thisForm.getValues()['data']);
							btn.up('window').close();
						}
					}
				},{
					xtype : 'button',
					text : 'anuluj',
					itemId : 'cancel',
					handler : function(btn){
						btn.up('window').close();
					}
				}
			]
		}
	],
	onWybranoDate : function(data){

	}
});