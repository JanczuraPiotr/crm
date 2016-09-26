/**
 * @task 4.2.0
 */
Ext.define('CRM.window.zarzad.Zarzad',{
	extend : 'Ext.window.Window',
	title : 'Administracja zarządem',
	collapsible : true,
	layout : 'hbox',
	modal : true,

	requires: [
		'CRM.window.ZmianaHasla',
		'CRM.grid.Zarzad'
	],

	constructor : function(){
		var def = this;

		def.ZmianaHaslaForm = new Ext.create('CRM.window.ZmianaHasla');
		def.ZarzadGrid = new Ext.create('CRM.grid.Zarzad');
		def.ZarzadGrid.setFirmaId(CRM.firma_id);
		def.ZarzadGrid.on('select',function( grid, record, index, eOpts ){
			def.ZmianaHaslaForm.setPracownik(record.data.id,record.data.nazwisko+' '+record.data.imie);
		});
		def.ZarzadGrid.on('deselect',function( grid, record, index, eOpts ){
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