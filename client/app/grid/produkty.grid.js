/**
 * @work 2014-09-24 extjs 4.2.2 >> extjs 5.0.1
 */
Ext.define('ProduktyGrid',{
	extend : 'Ext.grid.Panel',
	xtype : 'produkty-grid',

	constructor : function(){
	  var PG = this;

		PG.ProduktyStore = new Ext.create('ProduktyStore');
		PG.RowEditing = new Ext.create('Ext.grid.plugin.RowEditing',{
			clicksToMoveEditor: 1,
			autoCancel: false,
			errorSummary : false,
			listeners: {
				canceledit : function(editor, context, eOpts){
					console.log('ProduktyGrid::editing::canceledit');
					if( (context.record.data.id === '' || context.record.data.id === 0 ) && context.record.data.symbol === ""){
						PG.ProduktyStore.remove(PG.getView().getSelectionModel().getSelection()[0]);
					}
				}
			}
		});

		Ext.apply(PG,{
				pageSize : 10,
				store : PG.ProduktyStore,
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
						store : PG.ProduktyStore,
						pageSize : 30,
						displayMsg : '',
						displayInfo: true
					},{
						text : 'dodaj',
						scope : PG,
						handler : function(){
								var rec = new ProduktyModel({bank_id:PG.bank_id ,symbol:'',nazwa:'',opis:'',data_od:'',data_do:''});
								rec.set('tmpId' , Ext.id());
								PG.ProduktyStore.insert(0, rec);
								PG.RowEditing.startEdit(0,1);
						}
					},{
						text : 'usuń',
						scope : PG,
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
											PG.ProduktyStore.remove(selection);
										}
									}
								}
							);
						}
					}
				], // bbar

				plugins:[
					PG.RowEditing
				]

		});
		PG.superclass.constructor.call(this,arguments);
		PG.getSelectionModel().on('selectionchange', PG.onSelectionChange, PG);
	},
	initComponent : function(){
		var PG = this;
		PG.callParent();
		PG.view.on('expandbody',PG.onRowExpandBody);
		PG.view.on('collapsebody',PG.onRowCollapseBody);
	},
	onSelectionChange: function(selModel, records){
		var PG = this;
		PG.down('#delete').setDisabled(records.length === 0);
	},
	setNazwa : function(nazwa){
		var PG = this;
		PG.setTitle('Prodykty banku : '+nazwa);
	},
	setBankId : function(bank_id){
		var PG = this;
		PG.ProduktyStore.setBankId(bank_id);
		PG.bank_id = bank_id;
		if(bank_id > 0){
			PG.enable();
		}else{
			PG.setDisabled(true);
		}
	}
});

