/**
 * @work 4.2.0
 */
Ext.define('CRM.window.zarzad.GeneratorZadan',{
	extend : 'Ext.window.Window',
	title : 'Generator Zadań',
	collapsible : true,
	layout : 'hbox',

	requires : [
		'CRM.form.GeneratorZadan',
		'CRM.grid.BankiMin'
	],

	constructor : function(){
		var def = this;
		def.GeneratorZadanForm = new Ext.create('CRM.form.GeneratorZadan');

		def.BankiMinGrid = new Ext.create('CRM.grid.BankiMin',{
			height : 600,
			listeners : {
				select : function( thiss, record, index, eOpts ){
					def.GeneratorZadanForm.setBankId(record.data.id);
					def.GeneratorZadanForm.setNazwaBanku(record.data.nazwa);
				},
				deselect : function( thiss, record, index, eOpts ){
					def.GeneratorZadanForm.setBankId(0);
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
				def.GeneratorZadanForm
			],
			resizable : false
		});
		def.callParent();
	},
	listeners : {
		close : function(panel, eOpts){
			GeneratorZadanWindow = null;
		}
	}
});

var GeneratorZadanWindow = null;
