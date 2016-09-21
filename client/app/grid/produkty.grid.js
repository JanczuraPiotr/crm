/**
 * @task 4.2.0
 */
Ext.define('ProduktyGrid',{
	extend : 'Ext.grid.Panel',
	xtype : 'produkty-grid',

	constructor : function(){
	  var def = this;

		def.ProduktyStore = new Ext.create('ProduktyStore');
		def.RowEditing = new Ext.create('Ext.grid.plugin.RowEditing',{
			clicksToMoveEditor: 1,
			autoCancel: false,
			errorSummary : false,
			listeners: {
				canceledit : function(editor, context, eOpts){
					console.log('ProduktyGrid::editing::canceledit');
					if( (context.record.data.id === '' || context.record.data.id === 0 ) && context.record.data.symbol === ""){
						def.ProduktyStore.remove(def.getView().getSelectionModel().getSelection()[0]);
					}
				}
			}
		});

		Ext.apply(def,{
				pageSize : 10,
				store : def.ProduktyStore,
				disabled : true,
				columns:[
					{
						text : 'id',
						dataIndex : 'id',
						width : 30
					},{
						text : 'symbol',
						dataIndex : 'symbol',
						width : 50,
						editor : {
							xtype : 'textfield',
							allowBlank : false
						}
					},{
						text : 'nazwa',
						dataIndex : 'nazwa',
						width : 150,
						editor : {
							xtype : 'textfield',
							allowBlank : false
						}
//					},{
//						text : 'opis produktu',
//						dataIndex : 'opis',
//						width : 200,
////						editor : 'textarea'
////						editor : {
////							xtype :'textfield',
////							'default' : null
////						}
					},{
						xtype : 'datecolumn',
						text : 'od kiedy',
						dataIndex : 'data_od',
						renderer : Ext.util.Format.dateRenderer('Y-m-d'),
						editor : {
							xtype : 'datefield',
							format : 'Y-m-d',
							allowBlank : false
						},
						width : 85
					},{
						text : 'do kiedy',
						dataIndex : 'data_do',
						xtype : 'datecolumn',
						renderer : Ext.util.Format.dateRenderer('Y-m-d'),
						editor : {
							xtype : 'datefield',
							'default' : null,
							format : 'Y-m-d'
						},
						width : 85
					}
				], // columns
				bbar : [
					{
						xtype: 'pagingtoolbar',
						dock: 'bottom',
						store : def.ProduktyStore,
						pageSize : 30,
						displayMsg : '',
						displayInfo: true
					},{
						text : 'dodaj',
						scope : def,
						handler : function(){
								var rec = new ProduktyModel({bank_id:def.bank_id ,symbol:'',nazwa:'',opis:'',data_od:'',data_do:''});
								rec.set('tmpId' , Ext.id());
								def.ProduktyStore.insert(0, rec);
								def.RowEditing.startEdit(0,1);
						}
					},{
						text : 'usuń',
						scope : def,
						itemId : 'delete',
						handler : function(){
							var selection = this.getView().getSelectionModel().getSelection()[0];
							var cm = '';
							if (selection) {
								cm = 'Usuwasz firmę o nazwie : '+selection.data.nazwa;
							}
							Ext.Msg.confirm('Próbujesz usunąć rekord :' , cm ,
								function(btn){
									if(btn === 'yes'){
										if (selection) {
											def.ProduktyStore.remove(selection);
										}
									}
								}
							);
						}
					}
				], // bbar

				plugins:[
					def.RowEditing
				]

		});
		def.superclass.constructor.call(this,arguments);
		def.getSelectionModel().on('selectionchange', def.onSelectionChange, def);
	},
	initComponent : function(){
		var def = this;
		def.callParent();
		def.view.on('expandbody',def.onRowExpandBody);
		def.view.on('collapsebody',def.onRowCollapseBody);
	},
	onSelectionChange: function(selModel, records){
		var def = this;
		def.down('#delete').setDisabled(records.length === 0);
	},
	setNazwa : function(nazwa){
		var def = this;
		def.setTitle('Produkty banku : '+nazwa);
	},
	setBankId : function(bank_id){
		var def = this;
		def.ProduktyStore.setBankId(bank_id);
		def.bank_id = bank_id;
		if(bank_id > 0){
			def.enable();
		}else{
			def.setDisabled(true);
		}
	}
});

