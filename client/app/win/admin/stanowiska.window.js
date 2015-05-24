/**
 * @prace 2014-09-24 extjs 4.2.2 >> extjs 5.0.1
 */
Ext.define('StanowiskaWindow',{
	extend : 'Ext.window.Window',
	xtype : 'stanowiska-window',
	title : 'Stanowiska pracy - przypisanie pracowników',
	resizable : false,
	collapsible : true,
	layout : 'hbox',

	constructor : function(){
		var thisSW = this;
		thisSW.firma_id = -1;
		thisSW.placowka_id = -1;

		thisSW.StanowiskaGrid = new Ext.create('StanowiskaGrid');

		thisSW.FirmyOddzialyMinGrid = new Ext.create('FirmyOddzialyMinGrid',{
			listeners : {
				select : function( thiss, record, index, eOpts ){
					thisSW.placowka_id = record.data.id;
					thisSW.StanowiskaGrid.setPlacowka(thisSW.firma_id,thisSW.placowka_id);
					thisSW.StanowiskaGrid.setPlacowkaNazwa(record.data.nazwa);
				},
				deselect : function( thiss, record, index, eOpts ){
					console.log('FirmyOddzialyMinGrid::listeners::select');
					thisSW.placowka_id = 0;
					thisSW.StanowiskaGrid.setPlacowka(thisSW.fimra_id,thisSW.placowka_id);
				}
			}
		});

		thisSW.FirmyMinGrid = new Ext.create('FirmyMinGrid',{
			listeners : {
				select : function( thiss, record, index, eOpts ){
					thisSW.firma_id = record.data.id;
					thisSW.placowka_id = 0;
					thisSW.FirmyOddzialyMinGrid.setFirmaId(thisSW.firma_id);
					thisSW.FirmyOddzialyMinGrid.setFirmaNazwa(record.data.nazwa);
					thisSW.StanowiskaGrid.setPlacowka(thisSW.firma_id,thisSW.placowka_id);
				},
				deselect : function( thiss, record, index, eOpts ){
					thisSW.firma_id = 0;
					thisSW.placowka_id = 0;
					thisSW.FirmyOddzialyMinGrid.setFirmaId(thisSW.firma_id);
					thisSW.StanowiskaGrid.setPlacowka(thisSW.firma_id,thisSW.placowka_id);
				}
			}
		});

		thisSW.items = [
			thisSW.FirmyMinGrid,
			thisSW.FirmyOddzialyMinGrid,
			thisSW.StanowiskaGrid
		];
		thisSW.superclass.constructor.call(thisSW, arguments);
	},

	listeners : {
		close : function(panel, eOpts){
			StanowiskaWindow = null;
		}
	}

});

var StanowiskaWindow = null;