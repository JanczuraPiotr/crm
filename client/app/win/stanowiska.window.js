/**
 * @task 4.2.0
 */
Ext.define('StanowiskaWindow',{
	extend : 'Ext.window.Window',
	xtype : 'stanowiska-window',
	title : 'Stanowiska pracy - przypisanie pracowników',
	collapsible : true,
	layout : 'hbox',

	constructor : function(){
		var def = this;
		def.firma_id = -1;
		def.placowka_id = -1;

		def.StanowiskaGrid = new Ext.create('StanowiskaGrid');

		def.FirmyOddzialyMinGrid = new Ext.create('FirmyOddzialyMinGrid',{
			listeners : {
				select : function( thiss, record, index, eOpts ){
					def.placowka_id = record.data.id;
					def.StanowiskaGrid.setPlacowka(def.firma_id,def.placowka_id);
					def.StanowiskaGrid.setPlacowkaNazwa(record.data.nazwa);
				},
				deselect : function( thiss, record, index, eOpts ){
					def.placowka_id = 0;
					def.StanowiskaGrid.setPlacowka(def.fimra_id,def.placowka_id);
				}
			}
		});

		def.FirmyMinGrid = new Ext.create('FirmyMinGrid',{
			listeners : {
				select : function( thiss, record, index, eOpts ){
					def.firma_id = record.data.id;
					def.placowka_id = 0;
					def.FirmyOddzialyMinGrid.setFirmaId(def.firma_id);
					def.FirmyOddzialyMinGrid.setFirmaNazwa(record.data.nazwa);
					def.StanowiskaGrid.setPlacowka(def.firma_id,def.placowka_id);
				},
				deselect : function( thiss, record, index, eOpts ){
					def.firma_id = 0;
					def.placowka_id = 0;
					def.FirmyOddzialyMinGrid.setFirmaId(def.firma_id);
					def.StanowiskaGrid.setPlacowka(def.firma_id,def.placowka_id);
				}
			}
		});

		def.items = [
			def.FirmyMinGrid,
			def.FirmyOddzialyMinGrid,
			def.StanowiskaGrid
		];
		def.superclass.constructor.call(def, arguments);
	},

	listeners : {
		close : function(panel, eOpts){
			StanowiskaWindow = null;
		}
	}

});

var StanowiskaWindow = null; // @todo przepisać na singletona w konstruktorze