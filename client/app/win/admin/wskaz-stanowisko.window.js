/**
 * @task 4.2.0
 */
Ext.define('WskazStanowiskoWindow',{
	xtype : 'wskaz-stanowisko-window',
	extend : 'Ext.window.Window',
	title : 'Wskaż zwykłe stanowisko',
	collapsible : true,
	autoShow : false,
	layout : 'hbox',
	modal : true,

	constructor : function(){
		var thisWSW = this;
		thisWSW.firma_id = CRM.firma_id;
		thisWSW.firma_oddzial_id = -1;

		thisWSW.StanowiskaZwykleGrid = new Ext.create('StanowiskaZwykleGrid',{
			height : 600,
			listeners : {
				celldblclick : function( This, td, cellIndex, record, tr, rowIndex, e, eOpts ){
					thisWSW.wybranoStanowisko(record);
					thisWSW.close();
				}
			}
		});

		Ext.apply(thisWSW,{
			items : [
				thisWSW.StanowiskaZwykleGrid
			],
			resizable : false
		});

		thisWSW.superclass.constructor.call(thisWSW, arguments);
		thisWSW.StanowiskaZwykleGrid.setFirmaOddzial(thisWSW.firma_id,thisWSW.firma_oddzial_id);
	},
	setFirmaOddzial : function(firma_id,firma_oddzial_id){
		var thisWSW = this;
		thisWSW.firma_id = firma_id;
		thisWSW.firma_oddzial_id = firma_oddzial_id;
		thisWSW.StanowiskaZwykleGrid.setFirmaOddzial(firma_id,firma_oddzial_id);
	},
	wybranoStanowisko : function(record){

	}

});