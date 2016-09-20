/**
 * @task 4.2.0
 */
Ext.define('PracownicyGrid',{
	extend : 'Ext.grid.Panel',
	xtype : 'pracownicy-grid',
	height : 600,
	width : 1000,
	title : 'Pracownicy firmy',
	disabled : true,

	constructor : function(){
		var def = this;

		def.PracownicyStore = new Ext.create('PracownicyStore');
		def.RowEditing = new Ext.create('Ext.grid.plugin.RowEditing',{
			clicksToMoveEditor: 1,
			autoCancel: false,
			errorSummary : false,
			listeners: {
				canceledit : function(editor, context, eOpts){
					if( (context.record.data.id === '' || context.record.data.id === 0 ) && context.record.data.symbol === ""){
						def.store.remove(def.getView().getSelectionModel().getSelection()[0]);
					}
				}
			}
		});

		def.callParent();

		def.firma_id = -1;
	},

	initComponent : function(){
		var def = this;


		Ext.apply(def,{
				pageSize : 10,
				store : def.PracownicyStore,
				columns:[
					{
						text : 'id',
						dataIndex : 'id',
						width : 30
					},{
						text : 'nazwisko',
						dataIndex : 'nazwisko',
						width : 100,
						editor : {
							xtype : 'textfield',
							allowBlank : false
						}
					},{
						text : 'imie',
						dataIndex : 'imie',
						width : 80,
						editor : {
							xtype : 'textfield',
							allowBlank : false
						}
					},{
						text : 'pesel',
						dataIndex : 'pesel',
						width : 80,
						editor : {
							xtype : 'textfield',
							allowBlank : false
						}
					},{
						text : 'kod poczt',
						dataIndex : 'kod_poczt',
						width : 60,
						editor : {}
					},{
						text : 'miejscowosc',
						dataIndex : 'miejscowosc',
						width : 100,
						editor : {}
					},{
						text : 'ulica',
						dataIndex : 'ul',
						width : 100,
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
						format: 'Y-m-d',
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
						format : 'Y-m-d',
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
						scope : def,
						store : def.PracownicyStore,
						pageSize : 30,
						displayInfo: true
					},{
						text : 'dodaj',
						handler : function(){
								var rec = new PracownicyModel({
									firma_id:def.firma_id,
									nazwisko:'',
									nazwa:'',
									pesel:'',
									kod_poczt:'',
									miejsowosc:'',
									ul:'',
									nr_b:'',
									nr_l:'',
									tel:'',
									email:'',
									data_od:'',
									data_do:''
								});
								rec.set('tmpId' , Ext.id());
								def.PracownicyStore.insert(0, rec);
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
								cm = 'Usuwasz pracownika o nazwisku : '+selection.data.nazwisko;
							}
							Ext.Msg.confirm('Próbujesz usunąć rekord :' , cm ,
								function(btn){
									if(btn === 'yes'){
										if (selection) {
											def.PracownicyStore.remove(selection);
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
	},
	setFirmaNazwa : function(nazwa){
		var def = this;
		def.setTitle('Pracownicy firmy : '+nazwa);
	},
	setFirmaId : function(firma_id){
		var def = this;
		def.firma_id = firma_id;
		def.PracownicyStore.setFirmaId(firma_id);

		if(firma_id > 0){
			def.enable();
		}else{
			def.setDisabled(true);
			def.setTitle('Wybierz firmę');
		}
	}
});
