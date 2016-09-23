/**
 * Wskazanie prezesa z pośród pracowników firmy.
 * Edycja loginu i hasła pracownika.
 *
 * @work 4.2.0
 */
Ext.define('CRM.window.admin.Zarzad',{
	extend : 'Ext.window.Window',
	title : 'Zarządzanie prezesami',
	layout : 'hbox',
	modal : true,

	requires: [
		'CRM.grid.Firmy',
		'CRM.form.ZmianaHasla',
		'CRM.grid.Zarzad'
	],

	constructor : function(){
		var def = this;

		def.ZmianaHaslaForm = new Ext.create('CRM.form.ZmianaHasla');

		def.ZarzadGrid = new Ext.create('CRM.grid.Zarzad');
		def.ZarzadGrid.on('select',function( thiss, record, index, eOpts ){console.log('CRM.window.admin.Zarzad::CRM.grid.Zarząd::select');
			def.ZmianaHaslaForm.setPracownik(record.data.id,record.data.nazwisko+' '+record.data.imie);
		});
		def.ZarzadGrid.on('deselect',function( thiss, record, index, eOpts ){console.log('CRM.window.admin.Zarzad::CRM.grid.Zarzad::deselect');
			def.ZmianaHaslaForm.setPracownik(0,'');
		});
		def.FirmyStore = new Ext.create('CRM.store.Firmy');
		def.FirmyGrid = new Ext.create('Ext.grid.Panel',{
			title : 'firmy',
			autoLoad : false,
			autoSync : true,
			autoSave : false,
			height : 600,
			width : 400,
			store : def.FirmyStore,
			columns:[
				{
					text : 'symbol',
					dataIndex : 'symbol',
					width : 60
				},{
					text : 'nazwa',
					dataIndex : 'nazwa',
					width : 165
				},{
					text : 'miejscowosc',
					dataIndex : 'miejscowosc',
					width : 150
				}
			], // columns
			bbar : [
				{
					xtype: 'pagingtoolbar',
					dock: 'bottom',
					store : def.FirmyStore,
					pageSize : 30,
					displayMsg : '',
					displayInfo: false
				},{
						text : 'dodaj / usuń',
						handler : function(){
							var FG = new Ext.create('CRM.grid.Firmy');
							var FW = new Ext.create('Ext.window.Window',{
								title : 'Firmy',
								modal : true,
								autoShow : false,
								items : [
									FG
								],
								listeners : {
									close : function(panel , eOpts){
										def.FirmyStore.load();
									}
								}
							});
							FW.show();
						}
					}
			], // bbar
			listeners : {
				select : function( thiss, record, index, eOpts ){console.log('ZarzadWindow::FirmyGrid::select');
					def.ZarzadGrid.setFirmaId(record.data.id);
					def.ZarzadGrid.setFirmaNazwa(record.data.nazwa);
				},
				deselect : function( thiss, record, index, eOpts ){console.log('ZarzadWindow::FirmyGrid::deselect');
					def.ZarzadGrid.setFirmaId(0);
					def.ZmianaHaslaForm.disable(true);
				}
			}
		});


		Ext.apply(def,{
			items : [
					def.FirmyGrid,
					def.ZarzadGrid,
					def.ZmianaHaslaForm
			],
			resizable : false
		});

		def.superclass.constructor.call(def, arguments);

	},

	listeners : {
		close : function(panel , eOpts){
			ZarzadWindow = null;
		}
	}

});

var ZarzadWindow = null;