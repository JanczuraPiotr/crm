/**
 * @work 2014-09-24 extjs 4.2.2 >> extjs 5.0.1
 */
Ext.define('WyborOddzialuBankuWindow',{
	xtype : 'wybor-oddzialu-banku-window',
	extend : 'Ext.window.Window',
	title : 'Wybór Oddzialu Banku',
	collapsible : true,
	autoShow : false,
	layout : 'hbox',
	modal : true,

	constructor : function(){
		var thisWOBW = this;
		thisWOBW.firma_oddzial_id = -1;

		thisWOBW.OddzialyNiepowiazaneGrid = new Ext.create('OddzialyNiepowiazaneGrid',{
			disabled : true // Przed określeniem wybor-oddzialu-banku nie wiadomo o odziały jakiej wybor-oddzialu-banku chodzi więc edycja nie ma sensu
		});

		thisWOBW.OddzialyNiepowiazaneGrid.on('celldblclick',function( This, td, cellIndex, record, tr, rowIndex, e, eOpts ){
				thisWOBW.wybranoOddzialBanku(record.data.id);
				thisWOBW.close();
		});

		thisWOBW.BankiMinGrid = new Ext.create('BankiMinGrid',{
			height : 600,
			listeners : {
				select : function( thiss, record, index, eOpts ){
					thisWOBW.OddzialyNiepowiazaneGrid.setBankId(record.data.id);
					thisWOBW.OddzialyNiepowiazaneGrid.setBankNazwa(record.data.nazwa);
				},
				deselect : function( thiss, record, index, eOpts ){
					thisWOBW.OddzialyNiepowiazaneGrid.setBankId(0);
				}
			}
		});

		Ext.apply(thisWOBW,{
			items : [
					thisWOBW.BankiMinGrid,
					thisWOBW.OddzialyNiepowiazaneGrid
			],
			resizable : false
		});

		thisWOBW.superclass.constructor.call(thisWOBW, arguments);
		//------------------------------------------------------------------------------
		thisWOBW.OddzialyNiepowiazaneGrid.setFirmaOddzialId(thisWOBW.firma_oddzial_id);
	},

	wybranoOddzialBanku : function(bank_oddzial_id){

	}

});