/**
 * @task 4.2.0
 */
Ext.define('WyborOddzialuFirmyPanel',{
	extend : 'Ext.panel.Panel',

	initComponent : function(){
		console.log('WyborOddzialuFirmyPanel::constructor()');
		var WOFP = this;
		WOFP.placowka_id = -1;

		WOFP.FirmyOddzialyMinGrid = new Ext.create('FirmyOddzialyMinGrid',{
			listeners : {
				select : function( thiss, record, index, eOpts ){
					WOFP.placowka_id = record.data.id;
					WOFP.NadzorNadZadaniamiGrid.setPlacowka(WOFP.firma_id,WOFP.placowka_id);
					WOFP.NadzorNadZadaniamiGrid.setPlacowkaNazwa(record.data.nazwa);
				},
				deselect : function( thiss, record, index, eOpts ){
					console.log('FirmyOddzialyMinGrid::listeners::select');
					WOFP.placowka_id = 0;
					WOFP.NadzorNadZadaniamiGrid.setPlacowka(WOFP.fimra_id,WOFP.placowka_id);
				}
			}
		});

		WOFP.FirmyMinGrid = new Ext.create('FirmyMinGrid',{
			listeners : {
				select : function( thiss, record, index, eOpts ){
					WOFP.firma_id = record.data.id;
					WOFP.placowka_id = 0;
					WOFP.FirmyOddzialyMinGrid.setFirmaId(WOFP.firma_id);
					WOFP.FirmyOddzialyMinGrid.setFirmaNazwa(record.data.nazwa);
					WOFP.NadzorNadZadaniamiGrid.setPlacowka(WOFP.firma_id,WOFP.placowka_id);
				},
				deselect : function( thiss, record, index, eOpts ){
					WOFP.firma_id = 0;
					WOFP.placowka_id = 0;
					WOFP.FirmyOddzialyMinGrid.setFirmaId(WOFP.firma_id);
					WOFP.NadzorNadZadaniamiGrid.setPlacowka(WOFP.firma_id,WOFP.placowka_id);
				}
			}
		});
		Ext.apply(WOFP,{
			width : 490,
			layout : 'hbox',
			items : [
				WOFP.FirmyMinGrid,
				WOFP.FirmyOddzialyMinGrid
			]
		});

		WOFP.callParent();
//		thisWOFP.superclass.cosntructor.call(thisWOFP,arguments);
	},
	wybranoOddzialFirmy : function(placowka_id){

	}
});