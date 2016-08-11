/**
 * namespace client\app\win\admin
 * @work 2014-09-24 extjs 4.2.2 >> extjs 5.0.1
 */
Ext.define('PrzydzialZadanWindow',{
  xtype : 'przydzial-zadan-window',
  extend : 'Ext.window.Window',
  title : "Przydzial zadań",
	resizable : false,
	collapsible : true,
	layout : 'hbox',

	constructor : function(){
		console.log('PrzydzialZadanWindow::constructor()');
		var def = this;

		def.PrzydzialZadanGrid = new Ext.create('PrzydzialZadanGrid');
		def.PrzydzialZadanGrid.setTitle('zadania nieprzydzielone');
		def.PrzydzialZadanGrid.getStore().remoteFilter = true;
		def.PrzydzialZadanGrid.getStore().filter('stanowisko_id',null);
		def.PrzydzialZadanGrid.setDisabled(true);


		def.StanowiskaPrzydzialZadanGrid = new Ext.create('StanowiskaPrzydzialZadanGrid',{
			listeners : {
				select : function( thet, record, index, eOpts ){
					def.PrzydzialZadanGrid.setTitle('wybór zadań dla stanowiska : '+record.data.nazwa);
					def.PrzydzialZadanGrid.setStanowiskoId(record.data.id);
					def.PrzydzialZadanGrid.enable();
				},
				deselect : function( thet, record, index, eOpts ){
					def.PrzydzialZadanGrid.setDisabled(true);
					def.PrzydzialZadanGrid.setStanowiskoId(0);
					def.PrzydzialZadanGrid.setTitle('zadania nieprzydzielone');
				}
			}
		});

		def.PrzydzialZadanGrid.przydzielonoZadania = function(stanowisko_id,zadania){
			def.StanowiskaPrzydzialZadanGrid.przypisanoZadania(stanowisko_id, zadania);
		};

		def.FirmyOddzialyMinGrid = new Ext.create('FirmyOddzialyMinGrid',{
			listeners : {
				select : function( thet, record, index, eOpts ){
					def.placowka_id = record.data.id;
					def.StanowiskaPrzydzialZadanGrid.setPlacowka(def.firma_id,def.placowka_id);
					def.StanowiskaPrzydzialZadanGrid.setPlacowkaNazwa(record.data.nazwa);
					def.PrzydzialZadanGrid.setDisabled(true);
					def.PrzydzialZadanGrid.setStanowiskoId(0);
					def.PrzydzialZadanGrid.setTitle('zadania nieprzydzielone');
				},
				deselect : function( thet, record, index, eOpts ){
					def.StanowiskaPrzydzialZadanGrid.setPlacowka(def.fimra_id,def.placowka_id);
					def.PrzydzialZadanGrid.setDisabled(true);
					def.PrzydzialZadanGrid.setStanowiskoId(0);
					def.PrzydzialZadanGrid.setTitle('zadania nieprzydzielone');
				}
			}
		});

		def.FirmyMinGrid = new Ext.create('FirmyMinGrid',{
			listeners : {
				select : function( thet, record, index, eOpts ){
					def.firma_id = record.data.id;
					def.placowka_id = 0;
					def.FirmyOddzialyMinGrid.setFirmaId(def.firma_id);
					def.FirmyOddzialyMinGrid.setFirmaNazwa(record.data.nazwa);
					def.StanowiskaPrzydzialZadanGrid.setPlacowka(def.firma_id,def.placowka_id);
					def.PrzydzialZadanGrid.setDisabled(true);
					def.PrzydzialZadanGrid.setStanowiskoId(0);
					def.PrzydzialZadanGrid.setTitle('zadania nieprzydzielone');
				},
				deselect : function( thet, record, index, eOpts ){
					def.firma_id = 0;
					def.placowka_id = 0;
					def.FirmyOddzialyMinGrid.setFirmaId(def.firma_id);
					def.StanowiskaPrzydzialZadanGrid.setPlacowka(def.firma_id,def.placowka_id);
					def.PrzydzialZadanGrid.setDisabled(true);
					def.PrzydzialZadanGrid.setStanowiskoId(0);
					def.PrzydzialZadanGrid.setTitle('zadania nieprzydzielone');
				}
			}
		});

		Ext.apply(def,{
			items : [
				def.FirmyMinGrid,
				def.FirmyOddzialyMinGrid,
				def.StanowiskaPrzydzialZadanGrid,
				def.PrzydzialZadanGrid
			]
		});
		def.callParent(arguments);
		//def.superclass.constructor.call(def,arguments);
	},

	listeners : {
		close : function(panel,eOpts){
			// "singleton"
			PrzydzialZadanWindow = null;
		}
	}

});
// "singleton"
var PrzydzialZadanWindow = null; // @todo przepisać na singletona w konstruktorze
