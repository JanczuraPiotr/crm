/**
 * @confirm 2015-01-01 ExtJS 5.1.0
 */
Ext.define('KlienciWindow',{
	extend : 'Ext.window.Window',
	title : 'Klienci [dane podstawowe]',
	resizable : false,

	constructor : function(){
		var def = this;

//		Ext.define('KlienciWindow.OsobyPowiazaneGrid',{
//			extend : 'OsobyPowiazaneGrid',
//			width : 1300,
//			height : 150
//		});

		Ext.define('KlienciWindow.KlienciGrid',{
			extend : 'KlienciGrid',
			flex :1,
			listeners : {
				select : function( thiss, record, index, eOpts ){
					def.Edytor.setValue(record.data.opis);
//					def.OsobyPowiazaneGrid.setKlientId(record.data.id);
				},
				deselect : function( thiss, record, index, eOpts ){
					def.Edytor.setValue('');
				}
			}
		});
//		def.OsobyPowiazaneGrid = new Ext.create('KlienciWindow.OsobyPowiazaneGrid');
		def.KlienciGrid = new Ext.create('KlienciWindow.KlienciGrid');
		def.KlienciGrid.store.on('update', function( This, record, operation, modifiedFieldNames, eOpts ){
			def.Edytor.setValue(record.data.opis);
		});

		def.Edytor = Ext.create('Ext.form.field.HtmlEditor',{
			readOnly : true,
			width : 600,
			height : 505,
			enableAlignments : false,
			enableColors : false,
			enableFont : false,
			enableFontSize : false,
			enableFormat : false,
			enableLinks : false,
			enableLists : false,
			enableSourceEdit : false
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
								def.KlienciGrid,
								def.Edytor
							]
						},
//						def.OsobyPowiazaneGrid
					]
				}
			]

		});
		def.callParent();
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