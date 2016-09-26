/**
 * @task 4.2.0
 */
Ext.define('LoginForm', {
    extend: 'Ext.window.Window',

    autoShow: true,
    height: 170,
    width: 360,
    layout: {
        type: 'fit'
    },
    title: "Logowanie",
    closable: false,
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
          },
          {
            inputType: 'password',
            allowBlank: false,
            msgTarget: 'under',
            name: 'password',
            fieldLabel: "Hasło",
            maxLength: 15
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
            iconCls: 'cancel',
            text: 'Anuluj',
            handler : function(btn){
              var thisForm = btn.up('window').down('form').getForm();
              thisForm.findField('login').reset();
              thisForm.findField('password').reset();
            }
          },{
            xtype: 'button',
            itemId: 'submit',
            formBind: true,
            iconCls: 'key-go',
            text: "Loguj",
            handler : function(btn){
              var thisForm = btn.up('window').down('form').getForm();
              if(thisForm.isValid()){
                Ext.Ajax.request({
                  url: 'server/ajax/logowanie.php',
                  params: {
                    action : 'zaloguj',
                    login : thisForm.getValues()['login'],
                    password:thisForm.getValues()['password']
                  },
                  success: function(response){
										var resp = Ext.JSON.decode(response.responseText);
										console.log(resp);
										CRM.user_status = parseInt(resp.data.user_status);
										CRM.firma_id = parseInt(resp.data.firma_id);
										CRM.placowka_id = parseInt(resp.data.placowka_id);
										CRM.stanowisko_id = parseInt(resp.data.stanowisko_id);
										CRM.pracownik_id = parseInt(resp.data.pracownik_id);
										CRM.pracownik_nazwa = resp.data.pracownik_nazwa;
										switch(CRM.user_status){
                      case CRM.ADMIN_SUPER:
												location.href = 'client/admin-super.php';
                        break;
                      case CRM.ADMIN_ZWYKLY:
												location.hres = 'client/admin-zwykly.php';
                        break;
                      case CRM.ZARZAD_PREZES:
												location.href = 'client/zarzad-prezes.php';
                        break;
                      case CRM.ZARZAD_CZLONEK:
												location.href = 'client/zarzad-czlonek.php';
                        break;
                      case CRM.PRACOWNIK_KIEROWNIK:
												location.href = 'client/pracownik-kierownik.php';
                        break;
                      case CRM.PRACOWNIK_LIDER:
												location.href = 'client/pracownik-lider.php';
                        break;
                      case CRM.PRACOWNIK_ZWYKLY:
												location.href = 'client/pracownik-zwykly.php'
                        break;
                      default:
                        Ext.Msg.alert('błąd logowania', 'Spróbuj ponownie');
                    }
                  }
                });
              }
            }
          }
        ]
      }
    ],
});

Ext.onReady(function(){
  Ext.Ajax.request({
    url: 'server/ajax/logowanie.php',
    params: {
      action : 'get-user-typ'
    },
    success: function(response){
      var resp = Ext.JSON.decode(response.responseText);
      console.log(resp);
      CRM.user_status = parseInt(resp.data.user_status);
      CRM.firma_id = parseInt(resp.data.firma_id);
			CRM.placowka_id = parseInt(resp.data.placowka_id);
      CRM.stanowisko_id = parseInt(resp.data.stanowisko_id);
      CRM.pracownik_id = parseInt(resp.data.pracownik_id);
			CRM.pracownik_nazwa = resp.data.pracownik_nazwa;

      switch(CRM.user_status){
        case CRM.ADMIN_SUPER:
					location.href = 'client/admin-super.php';
          break;
        case CRM.ADMIN_ZWYKLY:
					location.href = 'client/admin-zwykly.php';
          break;
        case CRM.ZARZAD_PREZES:
					location.href = 'client/zarzad-prezes.php';
          break;
        case CRM.ZARZAD_CZLONEK:
					location.href = 'client/zarzad-czlonek.php';
          break;
        case CRM.PRACOWNIK_KIEROWNIK:
					location.href = 'client/pracownik-kierownik.php';
          break;
        case CRM.PRACOWNIK_LIDER:
					location.href = 'client/pracownik-lider.php';
          break;
        case CRM.PRACOWNIK_ZWYKLY:
					location.href = 'client/pracownik-zwykly.php';
          break;
        default:
					console.log('4');
        Ext.create('LoginForm');
				console.log('2');
      }
    }
  });
});