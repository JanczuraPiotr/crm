/**
 * @confirm 2014-12-30
 */
Ext.define('ZmianaHaslaForm',{
	extend : 'Ext.form.Panel',
	title : 'Zmiana danych do logowania',

	constructor : function(){
		var def = this;

		Ext.apply(def,{
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
							name: 'login',
							fieldLabel: "Login",
							maxLength: 25,
							msgTarget: 'under'
						},{
							xtype : 'displayfield',
							value : '<i>Jeżeli nie zmieniasz loginu pozostaw pole puste.</i>'
						},{
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
								var thisForm = btn.up('window').down('form').getForm();
								thisForm.findField('password1').reset();
								thisForm.findField('password2').reset();
							}
						},{
							xtype: 'button',
							itemId: 'submit',
							formBind: true,
							text: "zmień",
							handler : function(btn){
								var thisForm = btn.up('window').down('form').getForm();
								if(thisForm.isValid()){
									Ext.Ajax.request({
										url: '../server/ajax/dane-logowania.php',
										params: {
											pracownik_id : def.pracownik_id,
											login : thisForm.getValues()['login'],
											password1 : thisForm.getValues()['password1'],
											password2 : thisForm.getValues()['password2']
										},
										success: function(response){
											var resp = Ext.JSON.decode(response.responseText);
											if(resp.success === false){
												Ext.Msg.alert('Błąd',resp.message);
												/**
												 * @todo przyjazny komunikat o błędzie
												 */
											}else if(resp.success === true){
												def.clear();
												Ext.Msg.alert('Sukces!','Zmieniono hasło');
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
		def.pracownik_id = -1;
		def.nazwa = '';
		def.superclass.constructor.call(def,arguments);
		def.setDisabled(true);
	},
	listeners : {
		blur : function(){
			return true;
		}
	},
	clear : function(){
		var def = this;
		var Form = def.getForm();
		Form.findField('login').reset();
		Form.findField('password1').reset();
		Form.findField('password2').reset();
	},
	setPracownik : function(pracownik_id,nazwa){
		var def = this;
		var Form = def.getForm();

		if(def.pracownik_id !== pracownik_id){

			def.setDisabled(true);
			def.title = '';
			Form.findField('login').reset();
			Form.findField('password1').reset();
			Form.findField('password2').reset();

			def.pracownik_id = pracownik_id;
			def.nazwa = nazwa;

			if(def.pracownik_id > 0){
				def.title = 'Zmiana hasła dla : '+nazwa;
				def.enable();
			}
		}
	}

});
