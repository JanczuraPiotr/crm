/**
 * @prace 2014-09-24 extjs 4.2.2 >> extjs 5.0.1
 */
Ext.define('DaneLogowaniaWindow',{
  extend : 'Ext.window.Window',
  xtype : 'dane-logowania-window',
  id : 'dane-logowania-window',
  title : "Zmiana danych do logowania",
	modal : true,
	resizable : false,

	constructor : function(){
		var obj = this;

		Ext.apply(obj,{
			login : '',
			width: 360,
			layout: {
					type: 'fit'
			},

			items: [
				{
					xtype: 'form',
					frame: false,
					bodyPadding: 15,
					defaults: {
						xtype: 'textfield',
						anchor: '100%',
						labelWidth: 100
					},
					items: [
						{
							name: 'login',
							fieldLabel: "Nowy login",
							maxLength: 25,
							msgTarget: 'under'
						},{
							xtype : 'displayfield',
							value : '<i>Jeżeli nie zmieniasz loginu pozostaw pole puste.</i>'
						},{
							fieldLabel: "Nowe hasło",
							inputType: 'password',
							name: 'password1',
							maxLength: 25,
							allowBlank: false,
							msgTarget: 'under'
						},{
							fieldLabel: "powtórz hasło",
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
								Ext.getCmp('dane-logowania-window').close();
							}
						},{
							xtype: 'button',
							itemId: 'submit',
							formBind: true,
							text: "zmień",
							handler : function(btn){
								var thisWin = btn.up('window');
								var thisForm = btn.up('window').down('form').getForm();
								if(thisForm.isValid()){
									Ext.Ajax.request({
										url: '../server/ajax/dane-logowania.php',
										params: {
											pracownik_id : ( CRM.pracownik_id > 0 ? CRM.pracownik_id : null),
											login : thisForm.getValues()['login'],
											password1 : thisForm.getValues()['password1'],
											password2 : thisForm.getValues()['password2']
										},
										success: function(response){
											var resp = Ext.JSON.decode(response.responseText);
											console.log(resp);
											if(resp.success === false){
												Ext.Msg.alert('Błąd',resp.message);
											}else if(resp.success === true){
												thisWin.close();
												Ext.Msg.alert('Sukces!','Zmieniono dane do logowania');
											}
										}
									});
								}
							}
						}
					]
				}
			]
		});
		obj.superclass.constructor.call(obj,arguments);
	},

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
	listeners : {
		close : function(panel,eOpts){
			DaneLogowaniaWindow = null;
		}
	}

});

var DaneLogowaniaWindow = null; // @todo przepisać na singletona w konstruktorze

