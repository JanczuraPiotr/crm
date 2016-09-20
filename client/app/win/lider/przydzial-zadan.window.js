/**
 * @task 4.2.0
 */
Ext.define('PrzydzialZadanWindow',{
  xtype : 'przydzial-zadan-window',
  extend : 'Ext.window.Window',
  title : "Przydzial zadań",
	resizable : false,
	collapsible : true,
	layout : 'hbox',

	constructor : function(){
		var thisPZW = this;
		thisPZW.firma_id = CRM.firma_id;
		thisPZW.placowka_id = CRM.placowka_id;

		thisPZW.PrzydzialZadanGrid = new Ext.create('PrzydzialZadanGrid');
		thisPZW.PrzydzialZadanGrid.setTitle('zadania nieprzydzielone');
		thisPZW.PrzydzialZadanGrid.getStore().remoteFilter = true;
		thisPZW.PrzydzialZadanGrid.getStore().filter('stanowisko_id',null);
		thisPZW.PrzydzialZadanGrid.setDisabled(true);

		thisPZW.StanowiskaPrzydzialZadanGrid = new Ext.create('StanowiskaPrzydzialZadanGrid',{
			listeners : {
				select : function( thiss, record, index, eOpts ){
					thisPZW.PrzydzialZadanGrid.setTitle('wybór zadań dla stanowiska : '+record.data.nazwa);
					thisPZW.PrzydzialZadanGrid.setStanowiskoId(record.data.id);
					thisPZW.PrzydzialZadanGrid.enable();
				},
				deselect : function( thiss, record, index, eOpts ){
					thisPZW.PrzydzialZadanGrid.setDisabled(true);
					thisPZW.PrzydzialZadanGrid.setStanowiskoId(0);
					thisPZW.PrzydzialZadanGrid.setTitle('zadania nieprzydzielone');
				}
			}
		});
		thisPZW.StanowiskaPrzydzialZadanGrid.setTitle('Podległe stanowiska');
		thisPZW.StanowiskaPrzydzialZadanGrid.setPlacowka(thisPZW.firma_id,thisPZW.placowka_id);
		thisPZW.StanowiskaPrzydzialZadanGrid.enable();

		thisPZW.PrzydzialZadanGrid.przydzielonoZadania = function(stanowisko_id,zadania){
			thisPZW.StanowiskaPrzydzialZadanGrid.przypisanoZadania(stanowisko_id, zadania);
		};


		Ext.apply(thisPZW,{
			items : [
				thisPZW.StanowiskaPrzydzialZadanGrid,
				thisPZW.PrzydzialZadanGrid
			]
		});

		thisPZW.superclass.constructor.call(thisPZW,arguments);
	},

	listeners : {
		close : function(panel,eOpts){
			PrzydzialZadanWindow = null;
		}
	}

});

var PrzydzialZadanWindow = null;
