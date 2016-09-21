/**
 * @done 4.2.0
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
      CRM.user_status = parseInt(resp.data.user_status);
      CRM.firma_id = parseInt(resp.data.firma_id);
      CRM.stanowisko_id = parseInt(resp.data.stanowisko_id);
      CRM.pracownik_id = parseInt(resp.data.pracownik_id);
		}
	});

  var tb = new Ext.Toolbar({
    renderTo : Ext.getBody(),
    items:[
			{
				text : 'klienci',
					handler : function(){
						try{
							KlienciWindow.show().expand();
						}catch(e){
							KlienciWindow = new Ext.create('KlienciWindow');
							KlienciWindow.show();
						}
					}
			},{
				text : 'praca',
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
					},{
            text : 'nadzór nad zadaniami - według stanowisk',
            handler : function(){
							if(NadzorNadZadaniamiStanowiskWindow === null){
								NadzorNadZadaniamiStanowiskWindow = new Ext.create('NadzorNadZadaniamiStanowiskWindow').show();
							}else{
								NadzorNadZadaniamiStanowiskWindow.show().expand();
							}
            }
					},{
            text : 'nadzór nad zadaniami - według klientow',
            handler : function(){
							if(NadzorNadZadaniamiKlientowWindow === null){
								NadzorNadZadaniamiKlientowWindow = new Ext.create('NadzorNadZadaniamiKlientowWindow').show();
							}else{
								NadzorNadZadaniamiKlientowWindow.show().expand();
							}
            }
					}
				]
			},{
        text : 'zarządzanie firmą',
        menu : [
          {
						text : 'oddziały firmy',
						handler : function(){
							if(FirmaOddzialyWindow === null){
								FirmaOddzialyWindow = new Ext.create('FirmaOddzialyWindow').show();
							}else{
								FirmaOddzialyWindow.show().expand();
							}
						}
					},{
						text : 'zarzad',
						handler : function(){
							if(ZarzadWindow === null){
								ZarzadWindow = new Ext.create('ZarzadWindow').show();
							}else{
								ZarzadWindow.show().expand();
							}
						}
					},{
						text : 'uprawnienia pracowników',
						handler : function(){
							if(UprawnieniaWindow === null){
								UprawnieniaWindow = new Ext.create('UprawnieniaWindow').show();
							}else{
								UprawnieniaWindow.show().expand();
							}
						}

					},{
            text : 'Pochodzenie klienta',
            handler : function(){
							if(PochodzenieKlientowWindow === null){
								PochodzenieKlientowWindow = new Ext.create('PochodzenieKlientowWindow').show();
							}else{
								PochodzenieKlientowWindow.show().expand();
							}
            }
					},{
            text : 'Status Klienta',
            handler : function(){
							if(StatusKlientaWindow === null){
								StatusKlientaWindow = new Ext.create('StatusKlientaWindow').show();
							}else{
								StatusKlientaWindow.show().expand();
							}
            }
					},{
            text : 'Pracownicy',
            handler : function(){
							if(PracownicyWindow === null){
								PracownicyWindow = new Ext.create('PracownicyWindow').show();
							}else{
								PracownicyWindow.show().expand();
							}
            }
					},{
            text : 'stanowiska pracy',
            handler : function(){
							if(StanowiskaWindow === null){
								StanowiskaWindow = new Ext.create('StanowiskaWindow').show();
							}else{
								StanowiskaWindow.show().expand();
							}
            }
					},{
            text : 'wiązanie firm i bankow',
            handler : function(){
							if(WiazanieFirmBankowWindow === null){
								WiazanieFirmBankowWindow = new Ext.create('WiazanieFirmBankowWindow').show();
							}else{
								WiazanieFirmBankowWindow.show().expand();
							}
            }
					},{
						text : 'zespoły',
						handler : function(btn){
							if(ZespolyWindow === null){
								ZespolyWindow = new Ext.create('ZespolyWindow').show();
							}else{
								ZespolyWindow.show().expand();
							}
						}
					}
        ]
			},{
				text : 'narzędzia',
				menu : [
					{
						text : 'Banki',
						handler : function(){
							if(BankiWindow === null){
								BankiWindow = new Ext.create('BankiWindow').show();
							}else{
								BankiWindow.show();
							}
						}
					},{
						text : 'Produkty Bankowe',
						handler : function(){
							if(ProduktyWindow === null){
								ProduktyWindow = new Ext.create('ProduktyWindow').show();
							}else{
								ProduktyWindow.show().expand();
							}
						}
					},{
            text : 'typy stanowisk pracy',
            handler : function(){
							if(StatusStanowiskaWindow === null){
								Ext.create('StatusStanowiskaWindow').show();
							}else{
								StatusStanowiskaWindow.show().expand();
							}
            }
					},{
						text : 'Słownik dokumentow bankowych',
						handler : function(){
							if(SlownikDokumentowWindow === null){
								SlownikDokumentowWindow = new Ext.create('SlownikDokumentowWindow').show();
							}else{
								SlownikDokumentowWindow.show().expand();
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
					Ext.create('HelpWindow','zarzad-prezes');
				}
			},{
				text : 'zalogowano jako : prezes zarządu',
				disabled : true
			}
    ]
  });
//	Ext.getBody().unmask();
//  var AdminZwyklyGrid = Ext.create('AdminZwyklyGrid').render();
//  var PanelAdminSuper = Ext.create('AdminSuperTabs').render(Ext.getBody());


});
