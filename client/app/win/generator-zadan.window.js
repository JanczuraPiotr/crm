/**
 * @task 4.2.0
 */
Ext.define('GeneratorZadanWindow',{
	xtype : 'generator-zadan-window',
	extend : 'Ext.window.Window',
	title : 'Generator Zadań',
	collapsible : true,
	layout : 'hbox',

	constructor : function(){
		var def = this;
		def.GeneratorZadanForm = new Ext.create('GeneratorZadanForm');

		def.BankiMinGrid = new Ext.create('BankiMinGrid',{
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
		def.callParent(arguments);
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

var GeneratorZadanWindow = null; // @todo przepisać na singletona w konstruktorze
