/**
 * @task 4.2.0
 */
Ext.define('ProduktyWindow',{
	xtype : 'produkty-window',
	extend : 'Ext.window.Window',
	title : 'Produkty Bankowe',
	collapsible : true,
	layout : 'hbox',

	constructor : function(){
		var def = this;

		def.ProduktyForm = new Ext.create('ProduktyForm');

		def.BankiMinGrid = new Ext.create('BankiMinGrid',{
			height : 600,
			listeners : {
				select : function( thiss, record, index, eOpts ){
					def.ProduktyForm.setBankId(record.data.id);
					def.ProduktyForm.setNazwaBanku(record.data.nazwa);
				},
				deselect : function( thiss, record, index, eOpts ){
					def.ProduktyForm.setBankId(0);
				}
			}
		});
		def.superclass.constructor.call(def, arguments);
	},

	initComponent : function(){
		var def = this;

		Ext.apply(def,{
			items : [
				def.BankiMinGrid,
				def.ProduktyForm
			],
			resizable : false
		});
		def.callParent();
	},
	listeners : {
		close : function(panel, eOpts){
			ProduktyWindow = null;
		}
	}
});

var ProduktyWindow = null; // @todo przepisać na singletona w konstruktorze
