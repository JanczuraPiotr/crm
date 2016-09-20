/**
 * @work 4.2.0
 */
Ext.define('KlienciWindow',{
	extend : 'Ext.window.Window',
	title : 'Klienci [dane podstawowe]',
	resizable : false,
	collapsible : true,

	constructor : function(){console.log('KlienciWindow::constructor');
		var def = this;

		Ext.define('KlienciWindow.KlienciGrid',{
			extend : 'KlienciGrid',
			flex :1,
			listeners : {
				select : function( thiss, record, index, eOpts ){
					console.log('KlienciWindow.KlienciGrid.select');
					def.Editor.setValue(record.data.opis);
				},
				deselect : function( thiss, record, index, eOpts ){
					console.log('KlienciWindow.KlienciGrid.deselect');
					def.Editor.setValue('');
				}
			}
		});

		def.KlienciGrid = Ext.create('KlienciWindow.KlienciGrid');
		def.KlienciGrid.store.on('update', function( This, record, operation, modifiedFieldNames, eOpts ){
			def.Editor.setValue(record.data.opis);
		});

		// @todo Ukryć toolbar
		def.Editor = Ext.create('Ext.form.field.HtmlEditor',{
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
								def.KlienciGrid,
								def.Editor
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
			KlienciWindow = null;
		}
	},
	EdytorSetValue : function(value){
		var def = this;
		def.Edytor.setValue(value);
	}
});
var KlienciWindow = null; // @todo przepisać na singletona w konstruktorze