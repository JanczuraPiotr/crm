/**
 * @task 4.2.0
 */
Ext.define('PracownicyWindow',{
	extend : 'Ext.window.Window',
	xtype :'pracownicy-window',
	title : 'Pracownicy firm',
	collapsible : true,
	layout : 'hbox',

	constructor : function(){
		var thisPW = this;

		thisPW.PracownicyGrid = new Ext.create('PracownicyGrid');

		thisPW.FirmyMinGrid = new Ext.create('FirmyMinGrid',{
			listeners : {
				select : function( thiss, record, index, eOpts ){
					thisPW.PracownicyGrid.setFirmaId(record.data.id);
					thisPW.PracownicyGrid.setFirmaNazwa(record.data.nazwa);
				},
				deselect : function( thiss, record, index, eOpts ){
					thisPW.PracownicyGrid.setFirmaId(0);
				}
			}
		});

		thisPW.superclass.constructor.call(thisPW, arguments);
	},

	initComponent : function(){
		var thisPW  = this;

		Ext.apply(thisPW,{
			items : [
				thisPW.FirmyMinGrid,
				thisPW.PracownicyGrid
			]
		});
		thisPW.callParent();
	},
	listeners : {
		close : function(panel, eOpts){
			PracownicyWindow = null;
		}
	}
});

var PracownicyWindow = null