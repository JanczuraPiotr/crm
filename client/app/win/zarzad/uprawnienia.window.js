/**
 * @confrim 2014-12-30
 */
Ext.define('UprawnieniaWindow',{
	extend : 'Ext.window.Window',
	xtype :'uprawnienia-window',
	title : 'Uprawnienia',
	collapsible : true,
	layout : 'hbox',

	constructor : function(){
		var def = this;

		def.ZmianaHaslaForm = new Ext.create('ZmianaHaslaForm');
		def.PracownicyMinGrid = new Ext.create('PracownicyMinGrid',{
			width : 280
		});
		def.PracownicyMinGrid.setFirmaId(CRM.firma_id);
		def.PracownicyMinGrid.on('select',function( thiss, record, index, eOpts ){
					def.ZmianaHaslaForm.setPracownik(record.data.id,record.data.nazwisko+' '+record.data.imie);
				});
		def.PracownicyMinGrid.on('deselect',function( thiss, record, index, eOpts ){
					def.ZmianaHaslaForm.setPracownik(0,'');
				});

		def.superclass.constructor.call(def, arguments);
	},

	initComponent : function(){
		var def  = this;

		Ext.apply(def,{
			items : [
				def.PracownicyMinGrid,
				def.ZmianaHaslaForm
			]
		});
		def.callParent();
	},
	listeners : {
		close : function(panel, eOpts){
			UprawnieniaWindow = null;
		}
	}
});

var UprawnieniaWindow = null