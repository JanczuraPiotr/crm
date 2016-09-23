/**
 * @wok 4.2.0
 */
Ext.application({

    name: 'CRM',
    appFolder: 'app',

    requires: [
			'CRM.store.Firmy',
			'CRM.grid.Firmy',
			'CRM.window.admin.Zarzad',
			'CRM.window.DaneLogowania',
			'CRM.window.Help'
		],


    launch: function() {
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
							text : 'Zarządzaj',
							menu : [
								{
									text : 'Dodaj, edytuj, usuń : firmę',
									handler : function(){
										try{
											Firmy.show().expand();
										}catch (e){
											Firmy = new Ext.create('Ext.window.Window',{
												modal : true,
												title : 'Edycja firm w systemie',
												items : [
													new Ext.create('CRM.grid.Firmy')
												],
												resizable : false,
												listeners : {
													close : function(){
														delete Firmy;
													}
												}
											}).show();
										}
									}
								},{
									text : 'Zarządzaj prezesami firm',
									handler : function(){
										try{
											ZarzadWindow.show().expand();
										}catch(e){
											ZarzadWindow = Ext.create('CRM.window.admin.Zarzad');
											ZarzadWindow.show();
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
										try{
											DaneLogowaniaWindow.show().expand();

										}catch(e){
											DaneLogowaniaWindow = new Ext.create('CRM.window.DaneLogowania');
											DaneLogowaniaWindow.show();
										}
									}
								}
							]
						},{
							text : 'pomoc',
							handler : function(){
								Ext.create('CRM.window.Help','admin-super');
							}
						},{
							text : 'zalogowano jako : super administrator',
							disabled : true
						}
					]
				});
			});
		}
});