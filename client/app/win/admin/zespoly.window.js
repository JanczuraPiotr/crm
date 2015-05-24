/**
 * @prace 2014-09-24 extjs 4.2.2 >> extjs 5.0.1
 */
Ext.define('ZespolyWindow',{
	extend : 'Ext.window.Window',
	xtype : 'zespoly-window',
	title : 'Grupy robocze',
	collapsible : true,
	layout : 'hbox',

	constructor : function(){
		var thisSW = this;
		thisSW.firma_id = CRM.firma_id;
		thisSW.firma_oddzial_id = -1;

		thisSW.ZespolyGrid = new Ext.create('ZespolyGrid');

		thisSW.LiderzyGrid = new Ext.create('LiderzyGrid',{
			listeners : {
				select : function( thiss, record, index, eOpts ){
					thisSW.ZespolyGrid.setLiderId(record.data.id);
				},
				deselect : function( thiss, record, index, eOpts ){
					thisSW.ZespolyGrid.setLiderId(0);
				}
			}
		});
		thisSW.LiderzyGrid.store.odswierzZespol = function(){
			thisSW.ZespolyGrid.store.load();
		};

		thisSW.FirmyOddzialyMinGrid = new Ext.create('FirmyOddzialyMinGrid',{
			listeners : {
				select : function( thiss, record, index, eOpts ){
					thisSW.firma_oddzial_id = record.data.id;
					thisSW.LiderzyGrid.setFirmaOddzial(record.data.nazwa,thisSW.firma_id,thisSW.firma_oddzial_id);
					thisSW.ZespolyGrid.setFirmaOddzial(thisSW.firma_id,thisSW.firma_oddzial_id);
					thisSW.ZespolyGrid.setLiderId(0);
				},
				deselect : function( thiss, record, index, eOpts ){
					thisSW.firma_oddzial_id = 0;
					thisSW.LiderzyGrid.setFirmaOddzial('',thisSW.firma_id,thisSW.firma_oddzial_id);
					thisSW.ZespolyGrid.setFirmaOddzial(thisSW.firma_id,thisSW.firma_oddzial_id);
					thisSW.ZespolyGrid.setLiderId(0);
				}
			}
		});

		thisSW.FirmyMinGrid = new Ext.create('FirmyMinGrid',{
			listeners : {
				select : function( thiss, record, index, eOpts ){
					thisSW.firma_id = record.data.id;
					thisSW.firma_oddzial_id = 0;
					thisSW.FirmyOddzialyMinGrid.setFirma(record.data.nazwa,thisSW.firma_id);
					thisSW.LiderzyGrid.setFirmaOddzial(record.data.nazwa,thisSW.firma_id,0);
					thisSW.ZespolyGrid.setFirmaOddzial(thisSW.firma_id,0);
					thisSW.ZespolyGrid.setLiderId(0);
				},
				deselect : function( thiss, record, index, eOpts ){
					thisSW.firma_id = 0;
					thisSW.firma_oddzial_id = 0;
					thisSW.FirmyOddzialyMinGrid.setFirma('',thisSW.firma_id);
					thisSW.LiderzyGrid.setFirmaOddzial('',0,0);
					thisSW.ZespolyGrid.setFirmaOddzial(0,0);
					thisSW.ZespolyGrid.setLiderId(0);
				}
			}
		});

		thisSW.items = [
			thisSW.FirmyMinGrid,
			thisSW.FirmyOddzialyMinGrid,
			thisSW.LiderzyGrid,
			thisSW.ZespolyGrid
		];

		thisSW.superclass.constructor.call(thisSW, arguments);
	},

	listeners : {
		close : function(panel, eOpts){
			ZespolyWindow = null;
		}
	}

});

var ZespolyWindow = null;