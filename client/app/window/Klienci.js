/**
 * @work 4.2.0
 */
Ext.define('CRM.window.Klienci',{
	extend : 'Ext.window.Window',
	title : 'Klienci [dane podstawowe]',

	requires : [
		'CRM.grid.Klienci'
	],

	resizable : false,
	collapsible : true,

	constructor : function(){console.log('CRM.window.Klienci::constructor');
		var def = this;

		def.gridKlienci = Ext.create('CRM.grid.Klienci',{
			flex :1,
			listeners : {
				select : function( grid, record, index, eOpts ){
					console.log('CRM.window.Klienci.KlienciGrid.select');
					def.htmlEditor.setValue(record.data.opis);
				},
				deselect : function( grid, record, index, eOpts ){
					console.log('CRM.window.Klienci.KlienciGrid.deselect');
					def.htmlEditor.setValue('');
				},
			},
		});
		def.gridKlienci.store.on('update', function( grid, record, operation, modifiedFieldNames, eOpts ){
			def.htmlEditor.setValue(record.data.opis);
		});

		// @todo Ukryć toolbar
		def.htmlEditor = Ext.create('Ext.form.field.HtmlEditor',{
			readOnly : true,
			width : 600,
			height : 505,
		});

		def.callParent(arguments);
	},

	initComponent : function(){
		var def = this;

		Ext.apply(def,{
			collapsible : true,
			maximizable : false,
			width : 1310,

			layout : {
				type : 'hbox',
				pack : 'start',
				align : 'strech'
			},

			items : [
				{
					layout : {
						type : 'vbox'
					},
					items : [
						{
							layout : {
								type : 'hbox'
							},
							items : [
								def.gridKlienci,
								def.htmlEditor
							]
						},
					]
				}
			],
			listeners : {
				close : function(panel,eOpts){console.log('CRM.window.Klienci.close');
					KlienciWindow = null;
				}
			},

		});
		def.callParent();
	},

	EdytorSetValue : function(value){
		var def = this;
		def.htmlEdytor.setValue(value);
	}
});
var KlienciWindow = null; // @todo przepisać na singletona w konstruktorze