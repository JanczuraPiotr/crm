/**
 * @task 4.2.0
 */
Ext.define('GeneratorZadanWindow',{
	extend : 'Ext.window.Window',
	title : 'Generator Zadań',
	collapsible : true,
	layout : 'hbox',

	constructor : function(){
		var def = this;
		def.generatorZadanForm = new Ext.create('GeneratorZadanForm');

		def.bankiMinGrid = new Ext.create('BankiMinGrid',{
			height : 600,
			listeners : {
				select : function( thiss, record, index, eOpts ){
					def.generatorZadanForm.setBankId(record.data.id);
					def.generatorZadanForm.setNazwaBanku(record.data.nazwa);
				},
				deselect : function( thiss, record, index, eOpts ){
					def.generatorZadanForm.setBankId(0);
				}
			}
		});
		def.firmyMinGrid = new Ext.create('FirmyMinGrid',{
			listeners : {
				select : function( thiss, record, index, eOpts ){
					def.generatorZadanForm.setFirmaId(record.data.id);
				},
				deselect : function( thiss, record, index, eOpts ){
					def.generatorZadanForm.setFirmaId(0);
				}
			}
		});
		def.superclass.constructor.call(def, arguments);
	},

	initComponent : function(){
		var def = this;

		Ext.apply(def,{
			items : [
				def.firmyMinGrid,
				def.bankiMinGrid,
				def.generatorZadanForm
			],
			resizable : false
		});
		def.callParent();
	},
	listeners : {
		close : function(panel, eOpts){
			generatorZadanWindow = null;
		}
	}
});

var generatorZadanWindow = null;
