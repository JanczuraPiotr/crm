Ext.define('ZmianaHaslaWindow',{
  extend : 'Ext.window.Window',
	id : 'zmiana-hasla-window',
  title : "Zmiana hasła",

	getLogin : function(){
		return this.login;
	},
	getUserTyp : function(){
		return this.user_typ;
	},
	setLogin : function(login){
		this.login = login;
		this.title = 'Zmiana hasła użytkownika : '+login;
	},
	setUserTyp : function(user_typ){
		this.user_typ = user_typ;
	},
	items: [
		{
			xtype: 'form',
			frame: false,
			bodyPadding: 15,
			defaults: {
				xtype: 'textfield',
				anchor: '100%',
				labelWidth: 60
			},
			items: [
				{
					fieldLabel: "Hasło",
					inputType: 'password',
					name: 'password1',
					maxLength: 25,
					allowBlank: false,
					msgTarget: 'under'
				},{
					fieldLabel: "powtórz",
					inputType: 'password',
					name: 'password2',
					maxLength: 25,
					allowBlank: false,
					msgTarget: 'under'
				}
			]
		}
	],
	dockedItems: [
		{
			xtype: 'toolbar',
			dock: 'bottom',
			items: [
				{
					xtype: 'tbfill'
				},{
					xtype: 'button',
					itemId: 'cancel',
					text: 'Anuluj',
					handler : function(btn){
						Ext.getCmp('zmiana-hasla-window').close();
					}
				},{
					xtype: 'button',
					itemId: 'submit',
					formBind: true,
					text: "zmień hasło",
					handler : function(btn){
						var thisWin = btn.up('window');
						var thisForm = btn.up('window').down('form').getForm();
						if(thisForm.isValid()){
							Ext.Ajax.request({
								url: '../server/ajax/zmiana-hasla.php',
								params: {
									user_typ : thisWin.getUserTyp(),
									login : thisWin.getLogin(),
									password1 : thisForm.getValues()['password1'],
									password2 : thisForm.getValues()['password2']
								},
								success: function(response){
									var data = Ext.JSON.decode(response.responseText);
									console.log(data);
									if(data.success === false){
										Ext.Msg.alert('Błąd',data.message);
									}else if(data.success === true){
										thisWin.close();
										Ext.Msg.alert('Sukces!','Zmieniono hasło użytkownikowi : '+thisWin.getLogin());
									}
								}
							});
						}
					}
				}
			]
		}
	],
	login : '',
	height: 170,
	width: 360,
	layout: {
			type: 'fit'
	},
	closeAction: 'close',
	modal : true,
	listeners : {
		close : function(panel,eOpts){
			ZmianaHaslaWindow = null;
		}
	}

});

var ZmianaHaslaWindow = null; // @todo przepisać na singletona w konstruktorze

