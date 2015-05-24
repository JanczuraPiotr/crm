/**
 * @prace 2014-09-24 extjs 4.2.2 >> extjs 5.0.1
 * namespace client\app\win
 */
Ext.define('ZadaniaNieprzydzieloneWindow',{
  xtype : 'zadania-nieprzydzielone-window',
  extend : 'Ext.window.Window',
  title : "Zadania nieprzydzielone",
	resizable : false,
	collapsible : true,

	constructor : function(){
		console.log('ZadaniaNieprzydzieloneWindow.constructor()');
		var def = this;

		def.ZadaniaNaglowekGrid = new Ext.create('ZadaniaNaglowekGrid');
		def.ZadaniaNaglowekGrid.getStore().remoteFilter = true;
		def.ZadaniaNaglowekGrid.getStore().filter('stanowisko_id',null);

		Ext.apply(def,{
			items : [
				def.ZadaniaNaglowekGrid
			]
		});

		def.superclass.constructor.call(def,arguments);
	},

	listeners : {
		close : function(panel,eOpts){
			// "singleton"
			ZadaniaNieprzydzieloneWindow = null;
		}
	}

});
// "singleton"
var ZadaniaNieprzydzieloneWindow = null; // @todo przepisać na singletona w konstruktorze
