/**
 * @work 2014-09-24 extjs 4.2.2 >> extjs 5.0.1
 */
Ext.define('ZarzadWindow',{
	xtype : 'zarzad-window',
	extend : 'Ext.window.Window',
	title : 'Administracja zarzadami',
	collapsible : true,
	layout : 'hbox',
	modal : true,

	constructor : function(){
		var def = this;

		def.ZarzadGrid = new Ext.create('ZarzadGrid');
		def.FirmyStore = new Ext.create('FirmyStore');
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
							var FG = new Ext.create('FirmyGrid');
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
				select : function( thiss, record, index, eOpts ){
					def.ZarzadGrid.setFirmaId(record.data.id);
					def.ZarzadGrid.setFirmaNazwa(record.data.nazwa);
				},
				deselect : function( thiss, record, index, eOpts ){
					def.ZarzadGrid.setFirmaId(0);
				}
			}
		});


		Ext.apply(def,{
			items : [
					def.FirmyGrid,
					def.ZarzadGrid
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

var ZarzadWindow = null; // @todo przepisać na singletona w konstruktorze