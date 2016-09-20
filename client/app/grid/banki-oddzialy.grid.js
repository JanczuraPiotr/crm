/**
 * @task 4.2.0
 */
Ext.define('BankiOddzialyGrid',{
	extend : 'Ext.grid.Panel',
	xtype : 'banki-oddzialy-grid',

	initComponent : function(){
		var BOG = this;
		 BOG.bank_id = 0;

		 BOG.BankiOddzialyStore = new Ext.create('BankiOddzialyStore');

		 BOG.RowEditing = new Ext.create('Ext.grid.plugin.RowEditing',{
			clicksToMoveEditor: 1,
			autoCancel: false,
			errorSummary : false,
			listeners: {
				canceledit : function(editor, context, eOpts){
					console.log('BankiOddzialyGrid::editing::canceledit');
					if( (context.record.data.id === '' || context.record.data.id === 0 ) && context.record.data.symbol === ""){
						 BOG.store.remove( BOG.getView().getSelectionModel().getSelection()[0]);
					}
				}
			}
		});

		Ext.apply(BOG,{
				pageSize : 10,
				height : 200,
				width : 1100,
				title : 'Oddziały banku',
				store :  BOG.BankiOddzialyStore,
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
					},{
						text : 'nip',
						dataIndex : 'nip',
						width : 80,
						'default' : null,
						editor : {}
					},{
						text : 'kod poczt',
						dataIndex : 'kod_poczt',
						width : 60,
						editor : {}
					},{
						text : 'miejscowosc',
						dataIndex : 'miejscowosc',
						width : 130,
						editor : {}
					},{
						text : 'ulica',
						dataIndex : 'ul',
						width : 130,
						editor : {}
					},{
						text : 'nr bud.',
						dataIndex : 'nr_b',
						width : 50,
						editor : {}
					},{
						text : 'nr lok.',
						dataIndex : 'nr_l',
						width : 50,
						editor : {}
					},{
						text : 'telefon',
						dataIndex : 'tel',
						width : 70,
						editor : {}
					},{
						text : 'email',
						dataIndex : 'email',
						editor : {}
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
			//				,{
			////					xtype : "actioncolumn",
			////					items : [
			////						{
			////							tooltip:"Edit",
			////							text : 'edytuj hasło',
			////							icon : 'ext/extjs/resources/images/'default'/button/btn.gif',
			////							handler : function(grid,rowNum,colNum){
			////								alert('asdfas');
			////							}
			////						}
			////					],
			////					editor:{
			////
			//					}
				], // columns
				bbar : [
					{
						xtype: 'pagingtoolbar',
						dock: 'bottom',
						scope :  BOG,
						store :  BOG.BankiOddzialyStore,
						pageSize : 30,
						displayInfo: true
					},{
						text : 'dodaj',
						handler : function(){
								var rec = new BankiOddzialyModel({bank_id: BOG.bank_id,symbol:'',nazwa:'',nip:'',kod_poczt:'',miejsowosc:'',ul:'',nr_b:'',nr_l:'',tel:'',email:'',data_od:'',data_do:''});
								rec.set('tmpId' , Ext.id());
								 BOG.BankiOddzialyStore.insert(0, rec);
								 BOG.RowEditing.startEdit(0,1);
						}
					},{
						text : 'usuń',
						scope :  BOG,
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
											 BOG.BankiOddzialyStore.remove(selection);
										}
									}
								}
							);
						}
					}
				], // bbar
				plugins:[
					 BOG.RowEditing
				]
		});
		 BOG.callParent();
		 BOG.getSelectionModel().on('selectionchange',  BOG.onSelectChange,  BOG);
	},

	onSelectChange: function(selModel, selections){
		var thisBOG = this;
		thisBOG.down('#delete').setDisabled(selections.length === 0);
	},
	setBankNazwa : function(nazwa){
		var thisBOG = this;
		thisBOG.setTitle('Oddziały banku : '+nazwa);
	},
	setBankId : function(bank_id){
		var thisBOG = this;
		if(bank_id > 0){
			thisBOG.bank_id = bank_id;
			thisBOG.enable();
			thisBOG.BankiOddzialyStore.clearFilter();
			thisBOG.BankiOddzialyStore.setBankId(bank_id);
			thisBOG.BankiOddzialyStore.filter('bank_id',bank_id);
			thisBOG.BankiOddzialyStore.load();
		}else{
			thisBOG.bank_id = 0;
			thisBOG.BankiOddzialyStore.clearFilter();
			thisBOG.BankiOddzialyStore.setBankId(bank_id);
			thisBOG.BankiOddzialyStore.filter('bank_id',bank_id);
			thisBOG.setDisabled(true);
			thisBOG.setTitle('Wybierz bank');
//			thisFG.BankOddzialyStore.load();
		}
	}
});
