/**
 * @prace 2014-09-24 extjs 4.2.2 >> extjs 5.0.1
 */
Ext.define('ZatrudnianieWindow',{
  extend : 'Ext.window.Window',
  title : "Zatrudnianie - wybierz datę i kliknij dwukrotnie",
	resizable : false,
	collapsible : true,
	modal : true,

	constructor : function(){
		console.log('ZatrudnianieWindow::costructor');
		console.log(arguments);
		var def = this;

		def.ZatrudnianieGrid = new Ext.create('ZatrudnianieGrid');

		def.ZatrudnianieGrid.onNieZatrudnono = function(record){
			def.nieZatrudniono(record);
		};

		def.callParent(arguments);

		def.on('przypisanodostanowiska',function(record){
			console.log('ZatrudnianieWindow::on(przypisanodostanowiska)')
			def.zatrudniono(record);
			return true;
		},def);
	},
	initComponent : function(){
		var def = this;

		Ext.apply(def,{
			items : [
				def.ZatrudnianieGrid
			]
		});

		def.callParent();
	},
	setStanowisko : function(firma_id,placowka_id,stanowisko_id){
		var def = this;
		def.ZatrudnianieGrid.setStanowisko(firma_id,placowka_id,stanowisko_id);
		def.stanowisko_id = stanowisko_id;
	},
	onNieZatrudniono : function(record){},
	nieZatrudniono : function(record){
		var def = this;
		def.onZatrudniono(record);
	},
	zatrudniono : function(record){
		var def = this;
		def.onZatrudniono(record);
		def.close();
	}
});

var ZatrudnianieWindow = null; // @todo przepisać na singletona w konstruktorze