/**
 * @task 4.2.0
 */
Ext.define('PrzydzialZadanWindow',{
  extend : 'Ext.window.Window',
  title : "Przydzial zadań",
	resizable : false,
	collapsible : true,
	layout : 'hbox',

	constructor : function(){
		var def = this;

		def.PrzydzialZadanGrid = new Ext.create('PrzydzialZadanGrid');
		def.PrzydzialZadanGrid.setTitle('zadania nieprzydzielone');
//		def.PrzydzialZadanGrid.getStore().remoteFilter = true;
//		def.PrzydzialZadanGrid.getStore().filter('stanowisko_id',null);
		def.PrzydzialZadanGrid.setStanowiskoId(null);
		def.PrzydzialZadanGrid.setDisabled(true);

		def.StanowiskaPrzydzialZadanGrid = new Ext.create('StanowiskaPrzydzialZadanGrid',{
			listeners : {
				select : function(row, record, index, eOpts ){
					def.PrzydzialZadanGrid.setTitle('wybór zadań dla stanowiska : '+record.data.nazwa);
					def.PrzydzialZadanGrid.setStanowiskoId(record.data.id);
					def.PrzydzialZadanGrid.enable();
				},
				deselect : function(row, record, index, eOpts ){
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
				select : function( row, record, index, eOpts ){
					def.placowka_id = record.data.id;
					def.StanowiskaPrzydzialZadanGrid.setPlacowka(def.firma_id,def.placowka_id);
					def.StanowiskaPrzydzialZadanGrid.setPlacowkaNazwa(record.data.nazwa);
					def.PrzydzialZadanGrid.setDisabled(true);
					def.PrzydzialZadanGrid.setStanowiskoId(0);
					def.PrzydzialZadanGrid.setTitle('zadania nieprzydzielone');
				},
				deselect : function( row, record, index, eOpts ){
					def.StanowiskaPrzydzialZadanGrid.setPlacowka(def.fimra_id,def.placowka_id);
					def.PrzydzialZadanGrid.setDisabled(true);
					def.PrzydzialZadanGrid.setStanowiskoId(0);
					def.PrzydzialZadanGrid.setTitle('zadania nieprzydzielone');
				}
			}
		});


		def.FirmyMinGrid = new Ext.create('FirmyMinGrid',{
			listeners : {
				select : function( row, record, index, eOpts ){
					def.firma_id = record.data.id;
					def.placowka_id = 0;
					def.FirmyOddzialyMinGrid.setFirmaId(def.firma_id);
					def.FirmyOddzialyMinGrid.setFirmaNazwa(record.data.nazwa);
					def.StanowiskaPrzydzialZadanGrid.setPlacowka(def.firma_id,def.placowka_id);
					def.PrzydzialZadanGrid.setDisabled(true);
					def.PrzydzialZadanGrid.setStanowiskoId(0);
					def.PrzydzialZadanGrid.setTitle('zadania nieprzydzielone');
				},
				deselect : function( row, record, index, eOpts ){
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

		def.superclass.constructor.call(def,arguments);
	},

	listeners : {
		close : function(panel,eOpts){
			PrzydzialZadanWindow = null;
		}
	}

});

var PrzydzialZadanWindow = null; // @todo przepisać na singletona w konstruktorze
