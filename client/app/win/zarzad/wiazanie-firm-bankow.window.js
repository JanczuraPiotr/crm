/**
 * @confirm 2014-09-23
 * @work 2014-09-24 extjs 4.2.2 >> extjs 5.0.1
 */
Ext.define('WiazanieFirmBankowWindow',{
	extend : 'Ext.window.Window',
	title : 'Wiazanie firm i banków',
	collapsible : true,
	layout : 'hbox',

	constructor : function(){
		var def = this;
		def.firma_oddzial_id = -1;

		def.WiazanieFirmBankowGrid = new Ext.create('WiazanieFirmBankowGrid');

		def.FirmyOddzialyMinGrid = new Ext.create('FirmyOddzialyMinGrid',{
			listeners : {
				select : function( thiss, record, index, eOpts ){
					console.log('WiazanieFirmBankow::FirmyOddzialyMinGrid::listeners::select');
					console.log(record.data);
					def.firma_oddzial_id = record.data.id;
					def.WiazanieFirmBankowGrid.setFirmaOddzial(CRM.firma_id,def.firma_oddzial_id);
					def.WiazanieFirmBankowGrid.setFirmaOddzialNazwa(record.data.nazwa);
				},
				deselect : function( thiss, record, index, eOpts ){
					console.log('WiazanieFirmBankow::FirmyOddzialyMinGrid::listeners::select');
					console.log(record.data);
					def.firma_oddzial_id = 0;
					def.WiazanieFirmBankowGrid.setFirmaOddzial(CRM.firma_id,def.firma_oddzial_id);
				}
			}
		});
		def.FirmyOddzialyMinGrid.setFirmaId(CRM.firma_id);


		def.items = [
			def.FirmyOddzialyMinGrid,
			def.WiazanieFirmBankowGrid
		];
		def.superclass.constructor.call(def, arguments);
	},

	listeners : {
		close : function(panel, eOpts){
			WiazanieFirmBankowWindow = null;
		}
	}

});

var WiazanieFirmBankowWindow = null;