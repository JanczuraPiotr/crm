/**
 * @task 4.2.0
 */
Ext.define('LiderzyzyzyWindow',{
	extend : 'Ext.window.Window',
	xtype : 'Liderzyzy-window',
	title : 'Stanowiska pracy - przypisanie pracowników',
	collapsible : true,
	layout : 'hbox',

	constructor : function(){
		var thisSW = this;
		thisSW.firma_id = -1;
		thisSW.placowka_id = -1;

		thisSW.LiderzyGrid = new Ext.create('LiderzyGrid');

		thisSW.FirmyOddzialyMinGrid = new Ext.create('FirmyOddzialyMinGrid',{
			listeners : {
				select : function( thiss, record, index, eOpts ){
					thisSW.placowka_id = record.data.id;
					thisSW.LiderzyGrid.setPlacowka(thisSW.firma_id,thisSW.placowka_id);
					thisSW.LiderzyGrid.setPlacowkaNazwa(record.data.nazwa);
				},
				deselect : function( thiss, record, index, eOpts ){
					console.log('FirmyOddzialyMinGrid::listeners::select');
					thisSW.placowka_id = 0;
					thisSW.LiderzyGrid.setPlacowka(thisSW.fimra_id,thisSW.placowka_id);
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
					thisSW.LiderzyGrid.setPlacowka(thisSW.firma_id,thisSW.placowka_id);
				},
				deselect : function( thiss, record, index, eOpts ){
					thisSW.firma_id = 0;
					thisSW.placowka_id = 0;
					thisSW.FirmyOddzialyMinGrid.setFirmaId(thisSW.firma_id);
					thisSW.LiderzyGrid.setPlacowka(thisSW.firma_id,thisSW.placowka_id);
				}
			}
		});

		thisSW.items = [
			thisSW.FirmyMinGrid,
			thisSW.FirmyOddzialyMinGrid,
			thisSW.LiderzyGrid
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