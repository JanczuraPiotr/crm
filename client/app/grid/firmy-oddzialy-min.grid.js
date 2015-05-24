/**
 * Grid wyświetlający oddziały firmy której identyfikator istawia metoda setFirmaId().
 * @confirm 2015-01-02 ExtJS 5.1.0
 */
Ext.define('FirmyOddzialyMinGrid',{
	extend : 'Ext.grid.Panel',

	constructor : function(){
		var def = this;
		def.disabled = true;
		def.width = 245;
		def.height = 600;
		def.firma_id = CRM.firma_id;

		def.FirmyOddzialyStore = new Ext.create('FirmyOddzialyStore');

		Ext.apply(def,{ // @todo przenieś do initComponent();
				pageSize : 10,
				title : 'Stanowiska pracy w oddziale',
				store : def.FirmyOddzialyStore,
				columns:[
					{
						text : 'symbol',
						dataIndex : 'symbol',
						width : 60,
						editor : {
							xtype : 'textfield',
							allowBlank : false
						}
					},{
						text : 'nazwa',
						dataIndex : 'nazwa',
						width : 165,
						editor : {
							xtype : 'textfield',
							allowBlank : false
						}
					}
				], // columns
				bbar : [
					{
						xtype: 'pagingtoolbar',
						dock: 'bottom',
						store : def.FirmyOddzialyStore,
						pageSize : 30,
						displayMsg : '',
						displayInfo: false
					}
				] // bbar
		});

		def.callParent(arguments);
		def.setFirmaId(CRM.firma_id);
	},
	setFirma : function(nazwa,firma_id){
		var def = this;
		def.setFirmaNazwa(nazwa);
		def.setFirmaId(firma_id);
	},
	setFirmaNazwa : function(nazwa){
		var def = this;
		if(nazwa){
			def.setTitle('Oddziały firmy : '+nazwa);
		}
	},
	setFirmaId : function(firma_id){
		var def = this;
		def.FirmyOddzialyStore.setFirmaId(firma_id);
		def.firma_id = firma_id;
		if(firma_id > 0){
			def.enable();
		}else{
			def.setDisabled(true);
			def.setTitle('Wybierz firmę');
		}
	}
});
