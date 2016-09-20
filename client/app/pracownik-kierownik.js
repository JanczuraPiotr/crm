/**
 * @task 4.2.0
 */
Ext.onReady(function(){
	Ext.tip.QuickTipManager.init();

	Ext.Ajax.request({
    url: '../server/ajax/logowanie.php',
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
		}
	});

	var tb = new Ext.Toolbar({
		renderTo : Ext.getBody(),
		items : [
			{
				text : 'praca z zadaniami',
				menu : [
					{
						text : 'generowanie zadan',
						handler : function(p1,p2,p3,p4){
							if(GeneratorZadanWindow === null){
								GeneratorZadanWindow = new Ext.create('GeneratorZadanWindow').show();
							}else{
								GeneratorZadanWindow.show().expand();
							}
						}
					},{
            text : 'przydzial zadań',
            handler : function(){
							if(PrzydzialZadanWindow === null){
								PrzydzialZadanWindow = new Ext.create('PrzydzialZadanWindow').show();
							}else{
								PrzydzialZadanWindow.show().expand();
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
                        action : 'wyloguj',
                      },
                      success: function(response){
                        var data = Ext.JSON.decode(response.responseText);
                        if(data.success === 'true'){
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
					Ext.create('HelpWindow','pracownik-kierownik');
				}
			},{
				text : 'zalogowano jako : kierownik placówki',
				disabled : true
			}
		]
	});
});
