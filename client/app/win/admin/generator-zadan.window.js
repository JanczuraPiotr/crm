/**
 * @work 2014-09-24 extjs 4.2.2 >> extjs 5.0.1
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
		thisGZW.FirmyMinGrid = new Ext.create('FirmyMinGrid',{
			listeners : {
				select : function( thiss, record, index, eOpts ){
					thisGZW.GeneratorZadanForm.setFirmaId(record.data.id);
				},
				deselect : function( thiss, record, index, eOpts ){
					thisGZW.GeneratorZadanForm.setFirmaId(0);
				}
			}
		});
		thisGZW.superclass.constructor.call(thisGZW, arguments);
	},

	initComponent : function(){
		var thisGZW = this;

		Ext.apply(thisGZW,{
			items : [
				thisGZW.FirmyMinGrid,
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
