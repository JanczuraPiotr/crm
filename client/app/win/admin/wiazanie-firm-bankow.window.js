/**
 * @task 4.2.0
 */
Ext.define('WiazanieFirmBankowWindow',{
	extend : 'Ext.window.Window',
	xtype : 'wiazanie-firm-bankow-window',
	title : 'Wiazanie firm i banków',
	collapsible : true,
	layout : 'hbox',

	constructor : function(){
		var thisSW = this;
		thisSW.firma_id = -1;
		thisSW.firma_oddzial_id = -1;

		thisSW.WiazanieFirmBankowGrid = new Ext.create('WiazanieFirmBankowGrid');

		thisSW.FirmyOddzialyMinGrid = new Ext.create('FirmyOddzialyMinGrid',{
			listeners : {
				select : function( thiss, record, index, eOpts ){
					console.log('WiazanieFirmBankow::FirmyOddzialyMinGrid::listeners::select');
					console.log(record.data);
					thisSW.firma_oddzial_id = record.data.id;
					thisSW.WiazanieFirmBankowGrid.setFirmaOddzial(thisSW.firma_id,thisSW.firma_oddzial_id);
					thisSW.WiazanieFirmBankowGrid.setFirmaOddzialNazwa(record.data.nazwa);
				},
				deselect : function( thiss, record, index, eOpts ){
					console.log('WiazanieFirmBankow::FirmyOddzialyMinGrid::listeners::select');
					console.log(record.data);
					thisSW.firma_oddzial_id = 0;
					thisSW.WiazanieFirmBankowGrid.setFirmaOddzial(thisSW.firma_id,thisSW.firma_oddzial_id);
				}
			}
		});

		thisSW.FirmyMinGrid = new Ext.create('FirmyMinGrid',{
			listeners : {
				select : function( thiss, record, index, eOpts ){
					console.log('WiazanieFirmBankow::FirmyMinGrid::listeners::select');
					console.log(record.data);
					thisSW.firma_id = record.data.id;
					thisSW.firma_oddzial_id = 0;
					thisSW.FirmyOddzialyMinGrid.setFirmaId(thisSW.firma_id);
					thisSW.FirmyOddzialyMinGrid.setFirmaNazwa(record.data.nazwa);
					thisSW.WiazanieFirmBankowGrid.setFirmaOddzial(thisSW.firma_id,thisSW.firma_oddzial_id);
				},
				deselect : function( thiss, record, index, eOpts ){
					console.log('WiazanieFirmBankow::FirmyMinGrid::listeners::select');
					console.log(record.data);
					thisSW.firma_id = 0;
					thisSW.firma_oddzial_id = 0;
					thisSW.FirmyOddzialyMinGrid.setFirmaId(thisSW.firma_id);
					thisSW.WiazanieFirmBankowGrid.setFirmaOddzial(thisSW.firma_id,thisSW.firma_oddzial_id);
				},
				cellclick : function( This, record, item, index, e, eOpts ){
					console.log('WiazanieFirmBankow::FirmyMinGrid::listeners::select');
					console.log(record.data);
				}
			}
		});

		thisSW.items = [
			thisSW.FirmyMinGrid,
			thisSW.FirmyOddzialyMinGrid,
			thisSW.WiazanieFirmBankowGrid
		];
		thisSW.superclass.constructor.call(thisSW, arguments);
	},

	listeners : {
		close : function(panel, eOpts){
			WiazanieFirmBankowWindow = null;
		}
	}

});

var WiazanieFirmBankowWindow = null;