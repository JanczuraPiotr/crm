/**
 * @task 4.2.0
 */
Ext.define('FirmyGrid',{
	extend : 'Ext.grid.Panel',
	title : 'Dane rejestracyjne firmy',

	initComponent : function(){
		var def = this;

		def.FirmyStore = new Ext.create('FirmyStore');

		def.RowEditing = new Ext.create('Ext.grid.plugin.RowEditing',{
			clicksToMoveEditor: 1,
			autoCancel: false,
			errorSummary : false,
			listeners: {
				canceledit : function(editor, context, eOpts){
					if( (context.record.data.id === '' || context.record.data.id === 0 ) && context.record.data.symbol === ""){
						def.FirmyStore.remove(def.getView().getSelectionModel().getSelection()[0]);
					}
				}
			}
		});



		Ext.apply(def,{
				pageSize : 10,
				height : 300,
				width : 1100,
				store : def.FirmyStore,
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
						renderer : Ext.util.Format.defaultValue(null),
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
				], // columns
				bbar : [
					{
						xtype: 'pagingtoolbar',
						dock: 'bottom',
						store : def.FirmyStore,
						pageSize : 30,
						displayInfo: true
					},{
						text : 'dodaj',
						scope : def,
						handler : function(){
								var rec = new FirmyModel({symbol:'',nazwa:'',nip:'',kod_poczt:'',miejsowosc:'',ul:'',nr_b:'',nr_l:'',tel:'',email:'',data_od:'',data_do:''});
								rec.set('tmpId' , Ext.id());
								def.FirmyStore.insert(0, rec);
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
											def.FirmyStore.remove(selection);
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
		def.callParent();
		def.getSelectionModel().on('selectionchange', def.onSelectChange, def);
	},

	onSelectChange: function(selModel, selections){
		var def = this;
		def.down('#delete').setDisabled(selections.length === 0);
	}

});
