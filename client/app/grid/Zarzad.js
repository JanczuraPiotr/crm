/**
 * @done 4.2.0
 */
Ext.define('CRM.grid.Zarzad',{
	extend : 'Ext.grid.Panel',
	xtype : 'zarzad-grid',
	title : 'Pracownicy firmy',
	disabled : true,
	firma_id : -1,

	requires : [
		'CRM.store.Zarzad',
		'CRM.grid.Pracownicy',
		'CRM.window.WyborDaty'
	],

	constructor : function(){
		var def = this;

		def.ZarzadStore = Ext.create('CRM.store.Zarzad');
		// Poniższe 2 wiersze pozwoliły na odczytanie wybranej firmy już przy pierwszym kliknięciu.
		// Bez tego dopiero drugie wywołanie getPrezesId() zwracało wybraną firmę.
		def.ZarzadStore.setFirmaId(0);
		def.ZarzadStore.load();

		def.callParent(arguments);
	},

	initComponent : function(){
		var def = this;
		def.disabled = true;
		def.width = 335;
		def.height = 600;

		Ext.apply(def,{
				pageSize : 10,
				store : def.ZarzadStore,
				columns:[
					{
						text : 'nazwisko',
						dataIndex : 'nazwisko',
						width : 100
					},{
						text : 'imie',
						dataIndex : 'imie',
						width : 80
					},{
						text : 'pesel',
						dataIndex : 'pesel',
						width : 80
					},{
						text : 'prezes',
						dataIndex : 'prezes',
						width : 50,
						renderer : function(val, meta, record, rowIndex, colIndex, store){
							var a = '<input type= "radio" name="radioPrezes" id="radioPrezes-'+record.data.id+'" style="margin-left:10px;"  ' + (val ? "checked='checked'" : "") + '/>';
							return a;
						}
					}
				], // columns
				bbar : [
					{
						xtype: 'pagingtoolbar',
						dock: 'bottom',
						scope : def,
						store : def.ZarzadStore,
						pageSize : 30,
						displayInfo: false
					},{
						text : 'dodaj / usuń',
						handler : function(){
							var PG = new Ext.create('CRM.grid.Pracownicy');
							PG.setFirmaId(def.firma_id);
							var PF = new Ext.create('Ext.window.Window',{
								modal : true,
								autoShow : false,
								items : [
									PG
								],
								listeners : {
									close : function(panel , eOpts){
										def.store.load();
									}
								}
							});
							PF.show();
						}
					}
				], // bbar
				listeners: {
					cellclick : function(cell, td, cellIndex, record, tr, rowIndex, e, eOpts ){console.log('CRM.grid.Zarzad::cellclick()');
						if(cellIndex === 3){
							var DataWindow = Ext.create('CRM.window.WyborDaty',{
								title : 'data ustanienia prezesem',
								onWybranoDate : function(data){
										Ext.Ajax.request({
											url : '../server/ajax/prezes.php?action=create',
											params : {
												pracownik_id : record.data.id,
												firma_id : record.data.firma_id,
												prezes : 1,
												data_od : data
											},
											success : function(response){console.log('CRM.grid.Zarzad::cellclick::ajax::success');
												var resp = Ext.JSON.decode(response.responseText);
												if(resp.success === true){
													def.store.reload();
												}else{
													Ext.Msg.alert('Błąd',resp.message);
												}
											}
										});
								}
							});
							DataWindow.show();
						}
					}
				}

		});
		def.callParent();
//		def.superclass.constructor.call(def,arguments);
	},
	getPrezes : function(firmaId){console.log('CRM.grid.Zarzad::getPrezesId()');
		return this.ZarzadStore.getPrezes(firmaId);
	},
	setFirmaNazwa : function(nazwa){
		var def = this;
		def.setTitle('Pracownicy firmy : '+nazwa);
	},
	setFirmaId : function(firma_id){
		var def = this;
		def.firma_id = firma_id;
		def.ZarzadStore.setFirmaId(firma_id);

		if(firma_id > 0){
			def.enable();
		}else{
			def.setDisabled(true);
			def.setTitle('Wybierz firmę');
		}
	}
});
