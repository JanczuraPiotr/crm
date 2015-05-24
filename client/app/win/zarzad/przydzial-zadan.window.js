/**
 * @confirm 2015-01-02 ExtJS 5.1.0
 */
Ext.define('PrzydzialZadanWindow',{
  xtype : 'przydzial-zadan-window',
  extend : 'Ext.window.Window',
  title : "Przydzial zadań",
	resizable : false,
	collapsible : true,
	layout : 'hbox',

	constructor : function(){
		var def = this;
		def.firma_id = CRM.firma_id;

		def.PrzydzialZadanGrid = new Ext.create('PrzydzialZadanGrid');
		def.PrzydzialZadanGrid.setDisabled(true);
		def.PrzydzialZadanGrid.setTitle('zadania nieprzydzielone');
		def.PrzydzialZadanGrid.setStanowiskoId(null);

		def.StanowiskaPrzydzialZadanGrid = new Ext.create('StanowiskaPrzydzialZadanGrid',{
			listeners : {
				select : function( row, record, index, eOpts ){
					def.PrzydzialZadanGrid.setTitle('wybór zadań dla stanowiska : '+record.data.nazwa);
					def.PrzydzialZadanGrid.setStanowiskoId(record.data.id);
					def.PrzydzialZadanGrid.enable();
				},
				deselect : function( row, record, index, eOpts ){
					def.PrzydzialZadanGrid.setDisabled(true);
					def.PrzydzialZadanGrid.setStanowiskoId(0);
					def.PrzydzialZadanGrid.setTitle('zadania nieprzydzielone');
				}
			}
		});
		def.PrzydzialZadanGrid.on('przydzielonozadanie',function(stanowisko_id,zadania){
			def.StanowiskaPrzydzialZadanGrid.przydzielonoZadania(stanowisko_id, zadania);
		});
		def.FirmyOddzialyMinGrid = new Ext.create('FirmyOddzialyMinGrid',{
			listeners : {
				select : function( thiss, record, index, eOpts ){
					def.placowka_id = record.data.id;
					def.StanowiskaPrzydzialZadanGrid.setPlacowka(def.firma_id,def.placowka_id);
					def.StanowiskaPrzydzialZadanGrid.setPlacowkaNazwa(record.data.nazwa);
					def.PrzydzialZadanGrid.setDisabled(true);
					def.PrzydzialZadanGrid.setStanowiskoId(0);
					def.PrzydzialZadanGrid.setTitle('zadania nieprzydzielone');
				},
				deselect : function( thiss, record, index, eOpts ){
					def.StanowiskaPrzydzialZadanGrid.setPlacowka(def.fimra_id,def.placowka_id);
					def.PrzydzialZadanGrid.setDisabled(true);
					def.PrzydzialZadanGrid.setStanowiskoId(0);
					def.PrzydzialZadanGrid.setTitle('zadania nieprzydzielone');
				}
			}
		});
		def.FirmyOddzialyMinGrid.setFirmaId(CRM.firma_id);

		Ext.apply(def,{
			items : [
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

var PrzydzialZadanWindow = null;
