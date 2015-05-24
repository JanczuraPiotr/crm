/**
 * @prace 2014-09-24 extjs 4.2.2 >> extjs 5.0.1
 */
Ext.define('ProduktyForm',{
	extend : 'Ext.form.Panel',
	xtype : 'produkty-form',
	title : 'produkty banku',

	initComponent : function(){
		var PF = this;
		PF.index = -1; // index rekordu umieszczonego w formularzy

		PF.DokumentyProduktuGrid = new Ext.create('DokumentyProduktuGrid',{
			width: 450,
			height : 268
		});
		PF.ProduktyGrid = new Ext.create('ProduktyGrid',{
			height : 574,
			width : 420,
			listeners : {
				select : function( thiss, record, index, eOpts ){
					var thisPG = this;
					PF.setProduktId(record.data.id, index);
					PF.DokumentyProduktuGrid.setProduktId(record.data.id);
					PF.getForm().loadRecord(record);

				},
				deselect : function( thiss, record, index, eOpts ){
					PF.setProduktId(0);
					PF.DokumentyProduktuGrid.setProduktId(0);
				}
			}
		});
		PF.ProduktyGrid.getStore().onWriteCreate = function(store,operation,eOpts){
			for(var record in operation.records){
				PF.DokumentyProduktuGrid.setProduktId(operation.records[record].data.id);
			}
		};

		Ext.apply(PF,{
			frame : false,
			border : 0,
			layout : 'hbox',
			width : 890,
			height : 600,
			store : PF.ProduktyGrid.getStore(),

			fieldDefaults: {
					labelAlign: 'top',
					labelWidth: 90,
					anchor: '100%'
			},

			items : [
				PF.ProduktyGrid,
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
									PF.store.getAt(PF.index).set('opis',field.value);
									PF.store.commitChanges();
//									thisPF.store.sync();
								}
							}
						},
						PF.DokumentyProduktuGrid
					]
				}
			]
		});

		PF.callParent();
	},

	setNazwaBanku : function(nazwa){
		var PF = this;
		PF.setNazwa(nazwa);
	},
	setNazwa : function(nazwa){
		var PF = this;
		PF.setTitle('Prodykty banku : '+nazwa);
	},
	setBankId : function(bank_id){
		var PF = this;
		PF.ProduktyGrid.setBankId(bank_id);
		PF.DokumentyProduktuGrid.setProduktId(0);
		PF.loadRecord(new ProduktyModel({bank_id:'' ,symbol:'',nazwa:'',opis:'',data_od:'',data_do:''}));
		PF.index = -1;
		PF.produkt_id = -1;
	},
	setProduktId : function(produkt_id,index){
		var PF = this;
		PF.produkt_id = produkt_id;
		PF.index = index;
	}
});