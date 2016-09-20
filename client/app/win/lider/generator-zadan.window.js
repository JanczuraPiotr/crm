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
		var thisGZW = this;
		thisGZW.GeneratorZadanForm = new Ext.create('GeneratorZadanForm');

		thisGZW.BankiMinGrid = new Ext.create('BankiMinGrid',{
			height : 600,
			listeners : {
				select : function( thiss, record, index, eOpts ){
					thisGZW.GeneratorZadanForm.setBankId(record.data.id);
					thisGZW.GeneratorZadanForm.setNazwaBanku(record.data.nazwa);
				},
				deselect : function( thiss, record, index, eOpts ){
					thisGZW.GeneratorZadanForm.setBankId(0);
				}
			}
		});
		thisGZW.superclass.constructor.call(thisGZW, arguments);
	},

	initComponent : function(){
		var thisGZW = this;

		Ext.apply(thisGZW,{
			items : [
				thisGZW.BankiMinGrid,
				thisGZW.GeneratorZadanForm
			],
			resizable : false
		});
		thisGZW.callParent();
	},
	listeners : {
		close : function(panel, eOpts){
			GeneratorZadanWindow = null;
		}
	}
});

var GeneratorZadanWindow = null;
