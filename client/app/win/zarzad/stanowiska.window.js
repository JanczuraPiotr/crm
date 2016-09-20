/**
 * @task 4.2.0
 */
Ext.define('StanowiskaWindow',{
	extend : 'Ext.window.Window',
	title : 'Stanowiska pracy - przypisanie pracowników',
	collapsible : true,
	layout : 'hbox',

	constructor : function(){
		var def = this;
		def.placowka_id = -1;

		def.StanowiskaGrid = new Ext.create('StanowiskaGrid');

		def.FirmyOddzialyMinGrid = new Ext.create('FirmyOddzialyMinGrid',{
			listeners : {
				select : function( row, record, index, eOpts ){
					def.placowka_id = record.data.id;
					def.StanowiskaGrid.setPlacowka(CRM.firma_id,def.placowka_id);
					def.StanowiskaGrid.setPlacowkaNazwa(record.data.nazwa);
				},
				deselect : function( row, record, index, eOpts ){
					def.placowka_id = 0;
					def.StanowiskaGrid.setPlacowka(CRM.fimra_id,def.placowka_id);
				}
			}
		});
		def.FirmyOddzialyMinGrid.setFirmaId(CRM.firma_id);

		def.items = [
			def.FirmyOddzialyMinGrid,
			def.StanowiskaGrid
		];

		// def.superclass.constructor.call(def, arguments);
		def.callParent(arguments);
	},

	listeners : {
		close : function(panel, eOpts){
			StanowiskaWindow = null;
		}
	}

});

var StanowiskaWindow = null; // @todo przepisać na singletona w konstruktorze