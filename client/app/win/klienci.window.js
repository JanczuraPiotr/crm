/**
 * @work 4.2.0
 */
Ext.define('KlienciWindow',{
	extend : 'Ext.window.Window',
	title : 'Klienci [dane podstawowe]',
	resizable : false,
	collapsible : true,

	constructor : function(){console.log('klienciWindow::constructor');
		var def = this;

		Ext.define('KlienciWindow.KlienciGrid',{
			extend : 'KlienciGrid',
			flex :1,
			listeners : {
				select : function( grid, record, index, eOpts ){
					console.log('KlienciWindow.KlienciGrid.select');
					def.htmlEditor.setValue(record.data.opis);
				},
				deselect : function( grid, record, index, eOpts ){
					console.log('KlienciWindow.KlienciGrid.deselect');
					def.htmlEditor.setValue('');
				}
			}
		});

		def.klienciGrid = Ext.create('KlienciWindow.KlienciGrid');
		def.klienciGrid.store.on('update', function( grid, record, operation, modifiedFieldNames, eOpts ){
			def.htmlEditor.setValue(record.data.opis);
		});

		// @todo Ukryć toolbar
		def.htmlEditor = Ext.create('Ext.form.field.HtmlEditor',{
			readOnly : true,
			width : 600,
			height : 505,
		});

		def.callParent();
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
								def.klienciGrid,
								def.htmlEditor
							]
						},
					]
				}
			]

		});
		def.callParent(arguments);
	},

	listeners : {
		close : function(panel,eOpts){
			klienciWindow = null;
		}
	},
	EdytorSetValue : function(value){
		var def = this;
		def.htmlEdytor.setValue(value);
	}
});
var klienciWindow = null; // @todo przepisać na singletona w konstruktorze