/**
 * @prace 2014-09-24 extjs 4.2.2 >> extjs 5.0.1
 */
Ext.define('UprawnieniaWindow',{
	extend : 'Ext.window.Window',
	xtype :'uprawnienia-window',
	title : 'Uprawnienia',
	collapsible : true,
	layout : 'hbox',

	constructor : function(){
		var thisUW = this;

		thisUW.ZmianaHaslaForm = new Ext.create('ZmianaHaslaForm');

		thisUW.PracownicyMinGrid = new Ext.create('PracownicyMinGrid',{
			width : 280,
			listeners : {
				select : function( thiss, record, index, eOpts ){
					console.log('UprawnieniaWindow::listeners::select');
					thisUW.ZmianaHaslaForm.setPracownik(record.data.id,record.data.nazwisko+' '+record.data.imie);
				},
				deselect : function( thiss, record, index, eOpts ){
					console.log('UprawnieniaWindow.listeners::deselect');
					thisUW.ZmianaHaslaForm.setPracownik(0,'');
				}
			}
		});

		thisUW.FirmyMinGrid = new Ext.create('FirmyMinGrid',{
			listeners : {
				select : function( thiss, record, index, eOpts ){
					thisUW.PracownicyMinGrid.setFirmaId(record.data.id);
					thisUW.PracownicyMinGrid.setFirmaNazwa(record.data.nazwa);
					thisUW.ZmianaHaslaForm.setPracownik(0,'');
				},
				deselect : function( thiss, record, index, eOpts ){
					thisUW.PracownicyMinGrid.setFirmaId(0);
					thisUW.ZmianaHaslaForm.setPracownik(0,'');
				}
			}
		});

		thisUW.superclass.constructor.call(thisUW, arguments);
	},

	initComponent : function(){
		var thisUW  = this;

		Ext.apply(thisUW,{
			items : [
				thisUW.FirmyMinGrid,
				thisUW.PracownicyMinGrid,
				thisUW.ZmianaHaslaForm
			]
		});
		thisUW.callParent();
	},
	listeners : {
		close : function(panel, eOpts){
			UprawnieniaWindow = null;
		}
	}
});

var UprawnieniaWindow = null