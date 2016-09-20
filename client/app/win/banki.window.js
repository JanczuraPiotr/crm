/**
 * @task 4.2.0
 */
Ext.define('BankiWindow',{
	xtype : 'banki-window',
	extend : 'Ext.window.Window',
	title : 'Banki',
	collapsible : true,

	constructor : function(){
		var def = this;

		def.superclass.constructor.call(def, arguments);
	},

	initComponent : function(){
		var def = this;

		def.BankiOddzialyGrid = new Ext.create('BankiOddzialyGrid',{
			disabled : true // Przed określeniem banki nie wiadomo o odziały jakiej banki chodzi więc edycja nie ma sensu
		});

		def.BankiGrid = Ext.create('BankiGrid',{
			listeners : {
				select : function( thiss, record, index, eOpts ){
					def.BankiOddzialyGrid.setBankId(record.data.id);
					def.BankiOddzialyGrid.setBankNazwa(record.data.nazwa);
				},
				deselect : function( thiss, record, index, eOpts ){
					def.BankiOddzialyGrid.setBankId(0);
				}
			}
		});

		Ext.apply(def,{
			items : [
					def.BankiGrid,
					def.BankiOddzialyGrid
			],
			resizable : false
		});
		def.callParent();
	},
	listeners : {
		close : function(panel, eOpts){
			BankiWindow = null;
		}
	}
});
var BankiWindow = null; // @todo przepisać na singletona w konstruktorze