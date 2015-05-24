/**
 * @prace 2014-09-24 extjs 4.2.2 >> extjs 5.0.1
 */
Ext.define('LiderzyGrid',{
	extend : 'Ext.grid.Panel',
	xtype : 'lider-grid',

	constructor : function(){
		var def = this;

		def.firma_id = -1;
		def.firma_oddzial_id = -1;

		def.LiderzyStore = new Ext.create('LiderzyStore');

		def.RowExpander = new Ext.create('Ext.grid.plugin.RowExpander',{
			selectRowOnExpand : true,
			expandOnDblClick : false,
			expandOnEnter : false,
			rowBodyTpl : [
				'<p>',
					'<b>Lider : </b> {pracownik}',
				'</p>'
			]
		});

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

		Ext.apply(def,{
				pageSize : 10,
				title : 'grupa i jej lider',
				width : 430,
				height : 600,
				store : def.LiderzyStore,
				columns:[
					{
						text : 'symbol',
						dataIndex : 'symbol',
						width : 60,
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
						store : def.LiderzyStore,
						pageSize : 30,
						displayInfo: false
					},{
						text : 'dodaj',
						scope : def,
						handler : function(){
							var WLW = Ext.create('WskazLideraWindow');
							WLW.setFirmaOddzial(def.firma_id,def.firma_oddzial_id);
							WLW.wybranoLidera = function(recPracownik){
								var recLider = new Ext.create('LiderzyModel',{
									id : null,
									symbol : null,
									nazwa : null,
									opis : null,
									tel : null,
									email : null,
									stanowisko_id : null,
									pracownik_id : null,
									pracownik : null,
									pesel : null,
									data_od : null,
									data_do : null
								});
								recLider.set('tmpId',Ext.id());
								recLider.set('stanowisko_id',recPracownik.data.id);
								recLider.set('pracownik_id',recPracownik.data.pracownik_id);
								recLider.set('pracownik',recPracownik.data.pracownik);
								recLider.set('pesel',recPracownik.data.pesel);
								recLider.set('tel',recPracownik.data.tel);
								recLider.set('email',recPracownik.data.email);
								def.LiderzyStore.insert(0,recLider);
								def.RowEditing.startEdit(0,1);
							}
							WLW.show();

						}
					},{
						text : 'usuń',
						disabled : true
// Sekcja odpowiada za przycisk usuwania zespolu. Wraz włączeniem tego kodu należy włączyć wiersz w metodzie onSelectionChange : thisSG.down('#delete').setDisabled(selections.length === 0);
//						scope : thisLG,
//						itemId : 'delete',
//						handler : function(){
//							var selection = this.getView().getSelectionModel().getSelection()[0];
//							var cm = '';
//							if (selection) {
//								cm = 'Usuwasz grupę roboczą o nazwie : '+selection.data.nazwa;
//							}
//							Ext.Msg.confirm('Próbujesz usunąć rekord : ' , cm ,
//								function(btn){
//									if(btn === 'yes'){
//										if (selection) {
//											thisLG.LiderzyStore.remove(selection);
//										}
//									}
//								}
//							);
//						}
					}
				], // bbar
				plugins:[
					def.RowEditing,
					def.RowExpander
				]
		});

		def.superclass.constructor.call(def, arguments);

//		def.getSelectionModel().on('selectionchange', def.onSelectChange, def);

		def.setDisabled(true);
	},
	setFirmaOddzialNazwa : function(nazwa){
		var def = this;
		def.setTitle('Grupy robocze w oddziale : '+nazwa);
	},
	setFirmaOddzial : function(nazwa, firma_id, firma_oddzial_id){
		var def = this;
		def.setFirmaOddzialNazwa(nazwa);
		if(def.firma_id !== firma_id){
			def.firma_id = firma_id;
			def.setDisabled(true);
		}
		def.firma_oddzial_id = firma_oddzial_id;
		def.LiderzyStore.setPlacowkaId(firma_oddzial_id);
		if(firma_oddzial_id > 0){
			def.enable();
		}else{
			def.setDisabled(true);
			def.setTitle('Wybierz oddział firmy');
		}
	},
	onSelectChange: function(selModel, selections){
		var def = this;
		def.down('#delete').setDisabled(selections.length === 0);
	}
});
