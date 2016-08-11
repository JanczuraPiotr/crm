/**
 * @work 2014-09-24 extjs 4.2.2 >> extjs 5.0.1
 */
Ext.define('WskazLideraWindow',{
	xtype : 'wskaz-lidera-window',
	extend : 'Ext.window.Window',
	title : 'Wskaż lidera',
	collapsible : true,
	autoShow : false,
	layout : 'hbox',
	modal : true,

	constructor : function(){
		var thisWLW = this;
		thisWLW.firma_id = CRM.firma_id;
		thisWLW.firma_oddzial_id = -1;

		thisWLW.StanowiskaLiderowGrid = new Ext.create('StanowiskaLiderowGrid',{
			height : 600,
			listeners : {
				celldblclick : function( This, td, cellIndex, record, tr, rowIndex, e, eOpts ){
					thisWLW.wybranoLidera(record);
					thisWLW.close();
				}
			}
		});

		Ext.apply(thisWLW,{
			items : [
					thisWLW.StanowiskaLiderowGrid
			],
			resizable : false
		});

		thisWLW.superclass.constructor.call(thisWLW, arguments);
		thisWLW.StanowiskaLiderowGrid.setFirmaOddzial(thisWLW.firma_id,thisWLW.firma_oddzial_id);
	},
	setFirmaOddzial : function(firma_id,firma_oddzial_id){
		var thisWLW = this;
		thisWLW.firma_id = firma_id;
		thisWLW.firma_oddzial_id = firma_oddzial_id;
		thisWLW.StanowiskaLiderowGrid.setFirmaOddzial(firma_id,firma_oddzial_id);
	},
	wybranoLidera : function(record){

	}

});