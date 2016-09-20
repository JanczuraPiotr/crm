/**
 * @task 4.2.0
 */
Ext.define('BankiGrid',{
	extend : 'Ext.grid.Panel',
	xtype : 'banki-grid',

	initComponent : function(){
		var BG = this;

		BG.BankiStore = new Ext.create('BankiStore');

		BG.RowEditing = new Ext.create('Ext.grid.plugin.RowEditing',{
			clicksToMoveEditor: 1,
			autoCancel: false,
			errorSummary : false,
			listeners: {
				canceledit : function(editor, context, eOpts){
					if( (context.record.data.id === '' || context.record.data.id === 0 ) && context.record.data.symbol === ""){
						BG.BankiStore.remove(BG.getView().getSelectionModel().getSelection()[0]);
					}
				}
			}
		});



		Ext.apply(BG,{
				pageSize : 10,
				height : 300,
				width : 1100,
				store : BG.BankiStore,
				columns : [
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
//						renderer : Ext.util.Format.defaultValue(null),
						'default' : null,
						editor : {
							'default' : null
						}
					},{
						text : 'kod poczt',
						dataIndex : 'kod_poczt',
						width : 60,
						editor : {
							'default' : null
						}
					},{
						text : 'miejscowosc',
						dataIndex : 'miejscowosc',
						width : 130,
						editor : {
							'default' : null
						}
					},{
						text : 'ulica',
						dataIndex : 'ul',
						width : 130,
						'default' : null,
						editor : {
						}
					},{
						text : 'nr bud.',
						dataIndex : 'nr_b',
						width : 50,
						editor : {
							'default' : null
						}
					},{
						text : 'nr lok.',
						dataIndex : 'nr_l',
						width : 50,
						editor : {
							'default' : null
						}
					},{
						text : 'telefon',
						dataIndex : 'tel',
						width : 70,
						editor : {
							'default' : null
						}
					},{
						text : 'email',
						dataIndex : 'email',
						editor : {
							'default' : null
						}
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
			////							icon : 'ext/extjs/resources/images/default/button/btn.gif',
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
						store : BG.BankiStore,
						pageSize : 30,
						displayInfo: true
					},{
						text : 'dodaj',
						scope : BG,
						handler : function(){
								var rec = new BankiModel({symbol:'',nazwa:'',nip:'',kod_poczt:'',miejsowosc:'',ul:'',nr_b:'',nr_l:'',tel:'',email:'',data_od:'',data_do:''});
								rec.set('tmpId' , Ext.id());
								BG.BankiStore.insert(0, rec);
								BG.RowEditing.startEdit(0,1);
						}
					},{
						text : 'usuń',
						scope : BG,
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
											BG.BankiStore.remove(selection);
										}
									}
								}
							);
						}
					}
				], // bbar
				plugins:[
					BG.RowEditing
				]
		});
		BG.callParent();
		BG.getSelectionModel().on('selectionchange', BG.onSelectChange, BG);
	},

	onSelectChange: function(selModel, selections){
		var thisBG = this;
		thisBG.down('#delete').setDisabled(selections.length === 0);
	}

});
