/**
 * @confirm 2014-12-30
 */
Ext.define('PracownicyMinGrid',{
	extend : 'Ext.grid.Panel',
	xtype : 'pracownicy-min-grid',

	constructor : function(){
		var def = this;
		 def.disabled = true;
		 def.width = 330;
		 def.height = 600;

		 def.PracownicyMinStore = new Ext.create('PracownicyStore');


		Ext.apply( def,{
				pageSize : 10,
				store :  def.PracownicyMinStore,
				columns:[
					{
						text : 'nazwisko',
						dataIndex : 'nazwisko',
						width : 100
					},{
						text : 'imie',
						dataIndex : 'imie',
						width : 80
					},{
						text : 'pesel',
						dataIndex : 'pesel',
						width : 80
					}
				], // columns
				bbar : [
					{
						xtype: 'pagingtoolbar',
						dock: 'bottom',
						scope :  def,
						store :  def.PracownicyMinStore,
						pageSize : 30,
						displayInfo: false
					}
				] // bbar
		});


		 def.title = 'Pracownicy firmy';
		 def.disabled = true;
		 def.firma_id = -1;
		 def.superclass.constructor.call( def,arguments);

	},

	setFirmaNazwa : function(nazwa){
		var def = this;
		def.setTitle('Pracownicy firmy : '+nazwa);
	},
	setFirmaId : function(firma_id){
		var def = this;
		def.firma_id = firma_id;
		def.PracownicyMinStore.setFirmaId(firma_id);

		if(firma_id > 0){
			def.enable();
		}else{
			def.setDisabled(true);
			def.setTitle('Wybierz firmę');
		}
	}
});
