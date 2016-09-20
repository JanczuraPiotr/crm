/**
 * @task 4.2.0
 */
Ext.define('FirmaGrid',{
	extend : 'Ext.grid.Panel',
	xtype : 'firma-grid',

	initComponent : function(){
		var FG = this;

		FG.FirmaStore = new Ext.create('FirmyStore');
		FG.FirmaStore.filter('id',CRM.firma_id);

		FG.RowEditing = new Ext.create('Ext.grid.plugin.RowEditing',{
			clicksToMoveEditor: 1,
			autoCancel: false,
			errorSummary : false,
			listeners: {
				canceledit : function(editor, context, eOpts){
					if( (context.record.data.id === '' || context.record.data.id === 0 ) && context.record.data.symbol === ""){
						FG.FirmaStore.remove(FG.getView().getSelectionModel().getSelection()[0]);
					}
				}
			}
		});



		Ext.apply(FG,{
				pageSize : 10,
				height : 110,
				width : 1100,
				title : 'Dane rejestracyjne firmy',
				store : FG.FirmaStore,
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
							default : null,
							format : 'Y-m-d'
						},
						width : 85
					}
				], // columns
				plugins:[
					FG.RowEditing
				]
		});
		FG.callParent();
	}
});
