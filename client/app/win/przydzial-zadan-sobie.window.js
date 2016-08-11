/**
 * @work 2014-09-24 extjs 4.2.2 >> extjs 5.0.1
 */
Ext.define('PrzydzialZadanSobieWindow',{
  xtype : 'przydzial-zadan-sobie-window',
  extend : 'Ext.window.Window',
  title : "Przydzial zadań sobie",
	resizable : false,
	collapsible : true,
	layout : 'hbox',

	constructor : function(){
		var def = this;
		def.firma_id = CRM.firma_id;
		def.placowka_id = CRM.placowka_id;

		def.PrzydzialZadanGrid = new Ext.create('PrzydzialZadanGrid');
		def.PrzydzialZadanGrid.setTitle('zadania nieprzydzielone');
		def.PrzydzialZadanGrid.getStore().remoteFilter = true;
		def.PrzydzialZadanGrid.getStore().filter('stanowisko_id',null);
		def.PrzydzialZadanGrid.setStanowiskoId(CRM.stanowisko_id);

		def.ZadaniaNaglowekGrid = new Ext.create('ZadaniaNaglowekGrid');
		def.ZadaniaNaglowekGrid.setTitle('zadania przydzielone');
		def.ZadaniaNaglowekGrid.getStore().remoteFilter = true;
		def.ZadaniaNaglowekGrid.getStore().filter('stanowisko_id',CRM.stanowisko_id);

		def.PrzydzialZadanGrid.przydzielonoZadania = function(stanowisko_id,zadania){
			console.log('PrzydzialZadanSobieWindow::PrzydzialZadanGrid');
			console.log(stanowisko_id);
			console.log(zadania);
			def.ZadaniaNaglowekGrid.przypisanoZadania(stanowisko_id, zadania);
		};

		Ext.apply(def,{
			items : [
				def.ZadaniaNaglowekGrid,
				def.PrzydzialZadanGrid
			]
		});

		def.superclass.constructor.call(def,arguments);
	},

	listeners : {
		close : function(panel,eOpts){
			PrzydzialZadanSobieWindow = null;
		}
	}

});

var PrzydzialZadanSobieWindow = null; // @todo przepisać na singletona w konstruktorze
