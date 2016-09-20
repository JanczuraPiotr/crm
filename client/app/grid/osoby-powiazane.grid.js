/**
 * @task 4.2.0
 */
Ext.define('OsobyPowiazaneGrid',{
	extend : 'Ext.grid.Panel',
	xtype : 'osoby-powiazane-grid',

//	constructor : function(){
//		var thisOPG = this;
////		thisOPG.superclass.constructor.call(this,arguments);
//
//		thisOPG.callParent();
//		thisOPG.setDisabled(true);
//	},

	initComponent : function(){
		var def = this;
		def.klient_id = 0;

		def.OsobyPowiazaneStore = new Ext.create('OsobyPowiazaneStore');

		def.RowEditing = new Ext.create('Ext.grid.plugin.RowEditing',{
			clicksToMoveEditor: 1,
			autoCancel: false,
			errorSummary : false,
			listeners: {
				edit : function( editor, context, eOpts){
					context.record.commit(); // Nie wiem czemu ale bez tego aktualizacja rekordu nie działa za każdym razem
				},
				canceledit : function(editor, context, eOpts){
					if( (context.record.data.id === '' || context.record.data.id === 0 ) && context.record.data.symbol === ""){
						def.store.remove(def.getView().getSelectionModel().getSelection()[0]);
					}
				}
			}
		});

		Ext.apply(def,{
				pageSize : 10,
				title : 'Osoby powiazane',
				store : def.OsobyPowiazaneStore,
				disabled : true,
				columns:[
					{
						text : 'id',
						dataIndex : 'id',
						width : 30
					},{
						text : 'nazwa',
						dataIndex : 'nazwa',

						editor : {
							xtype : 'textfield',
							allowBlank : false
						}
					},{
						text : 'imie',
						dataIndex : 'imie',
						width : 150,
						editor : {
							xtype : 'textfield'
						}
					},{
						text : 'pesel',
						dataIndex : 'pesel',
						width : 80,
						renderer : Ext.util.Format.defaultValue(null),
						'default' : null,
						editor : {
							'default' : null
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
						text : 'email',
						dataIndex : 'email',
						editor : {
							'default' : null
						}
					},{
						text : 'tel kom',
						dataIndex : 'telkom',
						width : 70,
						editor : {
							'default' : null
						}
					},{
						text : 'tel dom',
						dataIndex : 'teldom',
						width : 70,
						editor : {
							'default' : null
						}
					},{
						text : 'tel praca',
						dataIndex : 'telpraca',
						width : 70,
						editor : {
							'default' : null
						}
					}
				], // columns
				bbar : [
					{
						xtype: 'pagingtoolbar',
						dock: 'bottom',
						scope : def,
						store : def.OsobyPowiazaneStore,
						pageSize : 30,
						displayInfo: true
					},{
						text : 'dodaj',
						handler : function(){
								var rec = new OsobyPowiazaneModel({
									klient_id : def.klient_id,
									nazwa:'',
									imie:'',
									nip:'',
									pesel:'',
									kod_poczt:'',
									miejsowosc:'',
									ul:'',
									nr_b:'',
									nr_l:'',
									email:'',
									telkom:'',
									teldom:'',
									telpraca:''
								});
								rec.set('tmpId' , Ext.id());
								def.OsobyPowiazaneStore.insert(0, rec);
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
											def.OsobyPowiazaneStore.remove(selection);
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
		def.setDisabled(true);
		def.getSelectionModel().on('selectionchange', def.onSelectChange, def);
	},

	onSelectChange: function(selModel, selections){
		var def = this;
		def.down('#delete').setDisabled(selections.length === 0);
	},
	setKlientId : function(klient_id){
		var def = this;
		def.klient_id = klient_id;
		def.OsobyPowiazaneStore.setKlientId(klient_id);
		if(klient_id > 0){
			def.setDisabled(false);
		}else{
			def.setDisabled(true);
		}
	}
});
