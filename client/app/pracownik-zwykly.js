/**
 * @work 2014-09-24 extjs 4.2.2 >> extjs 5.0.1
 */
Ext.onReady(function(){
	Ext.tip.QuickTipManager.init();

	Ext.Ajax.request({
    url: '../server/ajax/logowanie.php',
    params: {
      action : 'get-user-typ'
    },
		async : false,
    success: function(response){
      var resp = Ext.JSON.decode(response.responseText);
      CRM.user_status = parseInt(resp.data.user_status);
      CRM.firma_id = parseInt(resp.data.firma_id);
			CRM.placowka_id = parseInt(resp.data.placowka_id);
      CRM.stanowisko_id = parseInt(resp.data.stanowisko_id);
      CRM.pracownik_id = parseInt(resp.data.pracownik_id);
			CRM.pracownik_nazwa = resp.data.pracownik_nazwa;
		}
	});

	var tb = new Ext.Toolbar({
		renderTo : Ext.getBody(),
		items : [
			{
				text : 'Klienci',
				handler : function(){
					Ext.getBody().mask('Start okna edycji klientów');
					if(KlienciWindow === null){
						KlienciWindow = new Ext.create('KlienciWindow').show();
					}else{
						KlienciWindow.show().expand();
					}
					Ext.getBody().unmask();
				}
			},{
				text : 'praca z zadaniami',
				menu : [
					{
            text : 'praca z zadaniami',
            handler : function(){
							if(PracaZZadaniamiWindow === null){
								PracaZZadaniamiWindow = new Ext.create('PracaZZadaniamiWindow').show();
							}else{
								PracaZZadaniamiWindow.show().expand();
							}
            }
					},{
						text : 'generowanie zadan (sobie)',
						handler : function(p1,p2,p3,p4){
							if(GeneratorZadanWindow === null){
								GeneratorZadanWindow = new Ext.create('GeneratorZadanWindow').show();
							}else{
								GeneratorZadanWindow.show().expand();
							}
						}
					},{
            text : 'pobranie zadań (sobie)',
            handler : function(){
							if(PrzydzialZadanSobieWindow === null){
								PrzydzialZadanSobieWindow = new Ext.create('PrzydzialZadanSobieWindow').show();
							}else{
								PrzydzialZadanSobieWindow.show().expand();
							}
            }
					}
				]
      },{
				text : 'narzędzia',
				menu : [
					{
            text : 'podgląd typów statusów zadań',
            handler : function(){
							if(StatusZadaniaWindow === null){
								StatusZadaniaWindow = new Ext.create('StatusZadaniaWindow').show();
							}else{
								StatusZadaniaWindow.show().expand();
							}
            }
					}
				]
			},{
        text : 'Moje konto',
        menu:[
          {
            text: 'Wyloguj',
            handler : function(){
              Ext.Msg.confirm('Wylogowywanie','Czy na pewno wylogować ?',
                function(btn){
                  if(btn === 'yes'){
                    Ext.Ajax.request({
                      url: '../server/ajax/logowanie.php',
                      params: {
                        action : 'wyloguj'
                      },
                      success: function(response){
                        var data = Ext.JSON.decode(response.responseText);
                        if(data.success === 'true'){
													CRM = {};
                          location.href="../index.php";
                        }
                      }
                    });
                  }
                }
              );
            }
          },{
            text: 'Zmień login/hasło',
            handler : function(){
							if(DaneLogowaniaWindow === null){
								DaneLogowaniaWindow = new Ext.create('DaneLogowaniaWindow').show();
							}else{
								DaneLogowaniaWindow.show();
							}
            }
          }
        ]
			},{
				text : 'pomoc',
				handler : function(){
					Ext.create('HelpWindow','pracownik-zwykly');
				}
			},{
				text : 'zalogowano jako : pracownik ['+CRM.pracownik_nazwa+']',
				disabled : true
			}
		]
	});
});
