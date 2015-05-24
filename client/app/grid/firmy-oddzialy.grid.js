/**
 * @confirm 2014-12-29
 */
Ext.define('FirmyOddzialyGrid',{
	extend : 'Ext.grid.Panel',
	xtype : 'firmy-oddzialy-grid',

	constructor : function(){
		var def = this;
		def.firma_id = CRM.firma_id;
		def.FirmyOddzialyStore = new Ext.create('FirmyOddzialyStore');


		def.RowEditing = new Ext.create('Ext.grid.plugin.RowEditing',{
			clicksToMoveEditor: 1,
			autoCancel: false,
			errorSummary : false,
			listeners: {
				canceledit : function(editor, context, eOpts){
					console.log('FirmyOddzialyGrid::editing::canceledit');
					if( (context.record.data.id === '' || context.record.data.id === 0 ) && context.record.data.symbol === ""){
						def.store.remove(def.getView().getSelectionModel().getSelection()[0]);
					}
				}
			}
		});
		def.callParent(arguments);
	},

	initComponent : function(){
		var def = this;

		Ext.apply(def,{
				pageSize : 10,
				height : 200,
				width : 1100,
				title : 'Oddziały firmy',
				store : def.FirmyOddzialyStore,
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
						scope : def,
						store : def.FirmyOddzialyStore,
						pageSize : 30,
						displayInfo: true
					},{
						text : 'dodaj',
						handler : function(){
								var rec = new FirmyOddzialyModel({
									firma_id:def.firma_id,
									symbol:'',
									nazwa:'',
									nip:'',
									kod_poczt:'',
									miejscowosc:'',
									ul:'',
									nr_b:'',
									nr_l:'',
									tel:'',
									email:'',
									data_od:'',
									data_do:''
								});
								rec.set('tmpId' , Ext.id());
								def.FirmyOddzialyStore.insert(0, rec);
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
											def.FirmyOddzialyStore.remove(selection);
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
		def.setFirmaId(CRM.firma_id);
		def.getSelectionModel().on('selectionchange', def.onSelectChange, def);
	},

	onSelectChange: function(selModel, selections){
		var def = this;
		def.down('#delete').setDisabled(selections.length === 0);
	},
	setFirmaNazwa : function(nazwa){
		var def = this;
		def.setTitle('Oddziały firmy : '+nazwa);
	},
	setFirmaId : function(firma_id){
		var def = this;
		def.FirmyOddzialyStore.setFirmaId(firma_id);
//		if(firma_id > 0){
//			def.firma_id = firma_id;
//			def.enable();
//			def.FirmyOddzialyStore.clearFilter();
//			def.FirmyOddzialyStore.filter('firma_id',firma_id);
//			def.FirmyOddzialyStore.load();
//		}else{
//			def.firma_id = 0;
//			def.FirmyOddzialyStore.clearFilter();
//			def.FirmyOddzialyStore.filter('firma_id',firma_id);
//			def.setDisabled(true);
//			def.setTitle('Wybierz firmę');
////			thisFG.FirmaOddzialyStore.load();
//		}
	}
});
