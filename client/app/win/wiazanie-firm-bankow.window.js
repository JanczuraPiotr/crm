/**
 * @deprecated Prawdopodobnie używana jest tylko klasa z katalogu dla zarządu
 * @prace 2014-09-24 extjs 4.2.2 >> extjs 5.0.1
 */
Ext.define('WiazanieFirmBankowWindow',{
	extend : 'Ext.window.Window',
	title : 'Wiazanie firm i banków',
	collapsible : true,
	layout : 'hbox',

	constructor : function(){
		var def = this;
		def.firma_id = -1;
		def.firma_oddzial_id = -1;

		def.WiazanieFirmBankowGrid = new Ext.create('WiazanieFirmBankowGrid');

		def.FirmyOddzialyMinGrid = new Ext.create('FirmyOddzialyMinGrid',{
			listeners : {
				select : function( thiss, record, index, eOpts ){
					def.firma_oddzial_id = record.data.id;
					def.WiazanieFirmBankowGrid.setFirmaOddzial(def.firma_id,def.firma_oddzial_id);
					def.WiazanieFirmBankowGrid.setFirmaOddzialNazwa(record.data.nazwa);
				},
				deselect : function( thiss, record, index, eOpts ){
					def.firma_oddzial_id = 0;
					def.WiazanieFirmBankowGrid.setFirmaOddzial(def.firma_id,def.firma_oddzial_id);
				}
			}
		});

		def.FirmyMinGrid = new Ext.create('FirmyMinGrid',{
			listeners : {
				select : function( thiss, record, index, eOpts ){
					def.firma_id = record.data.id;
					def.firma_oddzial_id = 0;
					def.FirmyOddzialyMinGrid.setFirmaId(def.firma_id);
					def.FirmyOddzialyMinGrid.setFirmaNazwa(record.data.nazwa);
					def.WiazanieFirmBankowGrid.setFirmaOddzial(def.firma_id,def.firma_oddzial_id);
				},
				deselect : function( thiss, record, index, eOpts ){
					def.firma_id = 0;
					def.firma_oddzial_id = 0;
					def.FirmyOddzialyMinGrid.setFirmaId(def.firma_id);
					def.WiazanieFirmBankowGrid.setFirmaOddzial(def.firma_id,def.firma_oddzial_id);
				},
				cellclick : function( This, record, item, index, e, eOpts ){
					console.log('WiazanieFirmBankow::FirmyMinGrid::listeners::select');
					console.log(record.data);
				}
			}
		});

		def.items = [
			def.FirmyMinGrid,
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

var WiazanieFirmBankowWindow = null; // @todo przepisać na singletona w konstruktorze