/**
 * @confirm 2014-12-31
 */
Ext.define('FirmyWindow',{
	extend : 'Ext.window.Window',
	title : 'Firmy',
	collapsible : true,

	initComponent : function(){
		var def = this;

		def.FirmyOddzialyGrid = new Ext.create('FirmyOddzialyGrid',{
			disabled : true // Przed określeniem firmy nie wiadomo o odziały jakiej firmy chodzi więc edycja nie ma sensu
		});

		def.FirmyGrid = Ext.create('FirmyGrid',{
			listeners : {
				select : function( thiss, record, index, eOpts ){
					def.FirmyOddzialyGrid.setFirmaId(record.data.id);
					def.FirmyOddzialyGrid.setFirmaNazwa(record.data.nazwa);
				},
				deselect : function( thiss, record, index, eOpts ){
					def.FirmyOddzialyGrid.setFirmaId(0);
				}
			}
		});

		Ext.apply(def,{
			items : [
					def.FirmyGrid,
					def.FirmyOddzialyGrid
			],
			resizable : false
		});
		def.callParent();
	},
	listeners : {
		close : function(panel , eOpts){
			FirmyWindow = null;
		}
	}
});

var FirmyWindow = null; // @todo przepisać na singletona w konstruktorze