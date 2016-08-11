/**
 * @work 2014-12-30 Edycja przed chwilą dodanego rekordu nie uaktywnia operacji zapisu na server
 * @work 2014-09-24 extjs 4.2.2 >> extjs 5.0.1
 */
Ext.define('PracownicyWindow',{
	extend : 'Ext.window.Window',
	xtype :'pracownicy-window',
	title : 'Pracownicy firm',
	collapsible : true,
	layout : 'hbox',

	constructor : function(){
		var def = this;

		def.PracownicyGrid = new Ext.create('PracownicyGrid');

		def.FirmyMinGrid = new Ext.create('FirmyMinGrid',{
			listeners : {
				select : function( thiss, record, index, eOpts ){
					def.PracownicyGrid.setFirmaId(record.data.id);
					def.PracownicyGrid.setFirmaNazwa(record.data.nazwa);
				},
				deselect : function( thiss, record, index, eOpts ){
					def.PracownicyGrid.setFirmaId(0);
				}
			}
		});

		def.superclass.constructor.call(def, arguments);
	},

	initComponent : function(){
		var def  = this;

		Ext.apply(def,{
			items : [
				def.FirmyMinGrid,
				def.PracownicyGrid
			]
		});
		def.callParent();
	},
	listeners : {
		close : function(panel, eOpts){
			PracownicyWindow = null;
		}
	}
});

var PracownicyWindow = null;  // @todo przepisać na singletona w konstruktorze