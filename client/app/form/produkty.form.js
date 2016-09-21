/**
 * @work 4.2.0
 */
Ext.define('ProduktyForm',{
	extend : 'Ext.form.Panel',
	xtype : 'produkty-form',
	title : 'produkty banku',

	initComponent : function(){
		var def = this;
		def.index = -1; // index rekordu umieszczonego w formularzy

		def.DokumentyProduktuGrid = new Ext.create('DokumentyProduktuGrid',{
			width: 450,
			height : 268
		});
		def.ProduktyGrid = new Ext.create('ProduktyGrid',{
			height : 574,
			width : 420,
			listeners : {
				select : function( thiss, record, index, eOpts ){
					var thisPG = this;
					def.setProduktId(record.data.id, index);
					def.DokumentyProduktuGrid.setProduktId(record.data.id);
					def.getForm().loadRecord(record);

				},
				deselect : function( thiss, record, index, eOpts ){
					def.setProduktId(0);
					def.DokumentyProduktuGrid.setProduktId(0);
				}
			}
		});
		def.ProduktyGrid.getStore().onWriteCreate = function(store,operation,eOpts){
			for(var record in operation.records){
				def.DokumentyProduktuGrid.setProduktId(operation.records[record].data.id);
			}
		};

		Ext.apply(def,{
			frame : false,
			border : 0,
			layout : 'hbox',
			width : 890,
			height : 600,
			store : def.ProduktyGrid.getStore(),

			fieldDefaults: {
					labelAlign: 'top',
					labelWidth: 90,
					anchor: '100%'
			},

			items : [
				def.ProduktyGrid,
				{
					xtype : 'fieldset',
					margin: '0 0 0 0',
					defaults: {
						labelWidth: 90
					},
					items : [
						{
							xtype : 'textareafield',
							width : 450,
							height : 300,
							fieldLabel : 'OPIS PRODUKTU :',
							dataIndex : 'opis',
							name : 'opis',
							listeners : {
								blur : function(field , The, eOpts){
									def.store.getAt(def.index).set('opis',field.value);
									def.store.commitChanges();
//									thisPF.store.sync();
								}
							}
						},
						def.DokumentyProduktuGrid
					]
				}
			]
		});

		def.callParent();
	},

	setNazwaBanku : function(nazwa){
		var def = this;
		def.setNazwa(nazwa);
	},
	setNazwa : function(nazwa){
		var def = this;
		def.setTitle('Prodykty banku : '+nazwa);
	},
	setBankId : function(bank_id){
		var def = this;
		def.ProduktyGrid.setBankId(bank_id);
		def.DokumentyProduktuGrid.setProduktId(0);
		def.loadRecord(new ProduktyModel({bank_id:'' ,symbol:'',nazwa:'',opis:'',data_od:'',data_do:''}));
		def.index = -1;
		def.produkt_id = -1;
	},
	setProduktId : function(produkt_id,index){
		var def = this;
		def.produkt_id = produkt_id;
		def.index = index;
	}
});