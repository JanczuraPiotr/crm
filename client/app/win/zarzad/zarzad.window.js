/**
 * @confirm 2014-12-30
 */
Ext.define('ZarzadWindow',{
	extend : 'Ext.window.Window',
	title : 'Administracja zarządem',
	collapsible : true,
	layout : 'hbox',
	modal : true,

	constructor : function(){
		var def = this;

		def.ZmianaHaslaForm = new Ext.create('ZmianaHaslaForm');
		def.ZarzadGrid = new Ext.create('ZarzadGrid');
		def.ZarzadGrid.setFirmaId(CRM.firma_id);
		def.ZarzadGrid.on('select',function( thiss, record, index, eOpts ){
			def.ZmianaHaslaForm.setPracownik(record.data.id,record.data.nazwisko+' '+record.data.imie);
		});
		def.ZarzadGrid.on('deselect',function( thiss, record, index, eOpts ){
			def.ZmianaHaslaForm.setPracownik(0,'');
		});



		Ext.apply(def,{
			items : [
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