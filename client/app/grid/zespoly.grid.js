/**
 * @prace 2014-09-24 extjs 4.2.2 >> extjs 5.0.1
 */
Ext.define('ZespolyGrid',{
	extend : 'Ext.grid.Panel',
	xtype : 'zespoly-grid',

	constructor : function(){
		var obj = this;

		obj.firma_id = CRM.firma_id;
		obj.firma_oddzial_id = -1;

		obj.ZespolyStore = new Ext.create('ZespolyStore');

		obj.RowExpander = new Ext.create('Ext.grid.plugin.RowExpander',{
			selectRowOnExpand : true,
			expandOnDblClick : false,
			expandOnEnter : false,
			rowBodyTpl : [
				'<p>',
					'<b>Pracownik : </b> {pracownik_nazwa}',
				'</p>'
			]
		});

		obj.RowEditing = new Ext.create('Ext.grid.plugin.RowEditing',{
			clicksToMoveEditor: 1,
			autoCancel: false,
			errorSummary : false,
			listeners: {
				canceledit : function(editor, context, eOpts){
					console.log('LiderGrid::editing::canceledit');
					if( (context.record.data.id === '' || context.record.data.id === 0 ) && context.record.data.symbol === ""){
						obj.store.remove(obj.getView().getSelectionModel().getSelection()[0]);
					}
				}
			}
		});
		Ext.apply(obj,{
				width : 350,
				height : 600,
				title : 'członkowie grupy',
				store : obj.ZespolyStore,
				columns:[
					{
						text : 'stanowisko',
						dataIndex : 'stanowisko_nazwa',
						width : 130
					},{
						text : 'od kiedy',
						dataIndex : 'data_od',
						width : 85,
						renderer : Ext.util.Format.dateRenderer('Y-m-d'),
						editor : {
							xtype : 'datefield',
							format : 'Y-m-d',
							allowBlank : false
						}
					},{
						text : 'do kiedy',
						dataIndex : 'data_do',
						width : 85,
						renderer : Ext.util.Format.dateRenderer('Y-m-d'),
						editor : {
							xtype : 'datefield',
							format : 'Y-m-d'
						}
					}
				], // columns
				bbar : [
					{
						xtype: 'pagingtoolbar',
						dock: 'bottom',
						scope : obj,
						store : obj.ZespolyStore,
						pageSize : 30,
						displayInfo: false
					},{
						text : 'dodaj',
						handler : function(){
							var WSW = new Ext.create('WskazStanowiskoWindow');
							WSW.setFirmaOddzial(obj.firma_id,obj.firma_oddzial_id);
							WSW.wybranoStanowisko = function(recStanowisko){
								var recZespol = new Ext.create('ZespolyModel',{
									id : null,
									lider_id : null,
									stanowisko_id : null,
									stanowisko_symbol : null,
									stanowisko_nazwa : null,
									stanowisko_tel : null,
									stanowisko_email : null,
									pracownik_nazwa : null,
									pracownik_pesel : null,
									data_od : null,
									data_do : null
								});
								recZespol.set('tmpId',Ext.id());
								recZespol.set('lider_id',obj.lider_id);
								recZespol.set('stanowisko_id',recStanowisko.data.id);
								recZespol.set('stanowisko_symbol',recStanowisko.data.symbol);
								recZespol.set('stanowisko_nazwa',recStanowisko.data.nazwa);
								recZespol.set('pracownik_id',recStanowisko.data.pracownik_id);
								recZespol.set('pracownik_nazwa',recStanowisko.data.pracownik);
								recZespol.set('pracownik_pesel',recStanowisko.data.pesel);
								recZespol.set('tel',recStanowisko.data.tel);
								recZespol.set('email',recStanowisko.data.email);
								obj.ZespolyStore.insert(0,recZespol);
								obj.RowEditing.startEdit(0,1);
							}
							WSW.show();
						}
					},{
						text : 'usuń',
						disabled : true
// Sekcja odpowiada za przycisk usuwania zespolu. Wraz włączeniem tego kodu należy włączyć wiersz w metodzie onSelectionChange : thisSG.down('#delete').setDisabled(selections.length === 0);
//						scope : thisZG,
//						itemId : 'delete',
//						handler : function(){
//							var selection = this.getView().getSelectionModel().getSelection()[0];
//							var cm = '';
//							if (selection) {
//								cm = 'Usuwasz członka grupy roboczej o nazwie : '+selection.data.nazwa;
//							}
//							Ext.Msg.confirm('Próbujesz usunąć rekord : ' , cm ,
//								function(btn){
//									if(btn === 'yes'){
//										if (selection) {
//											thisZG.ZespolyStore.remove(selection);
//										}
//									}
//								}
//							);
//						}
					}
				], // bbar
				plugins:[
					obj.RowEditing,
					obj.RowExpander
				]
		});

		obj.superclass.constructor.call(obj, arguments);
		//------------------------------------------------------------------------------
		obj.getSelectionModel().on('selectionchange', obj.onSelectChange, obj);
		obj.view.on('expandbody', function(rowNode,recordStanowisko, expandRow) {
		});
		obj.view.on('collapsebody',function(rowNode, recordStanowisko, expandRow){
		});

		obj.setDisabled(true);
	},

	listeners : {
		cellclick : function( This, record, item, index, e, eOpts ){
			console.log('ZespolyychGrid::itemclick');
		}
	},
	onSelectChange: function(selModel, selections){
		//var thisSG = this;
		//thisSG.down('#delete').setDisabled(selections.length === 0);
	},
	setFirmaOddzial : function(firma_id, firma_oddzial_id){
		var obj = this;

		obj.firma_id = firma_id;
		obj.firma_oddzial_id = firma_oddzial_id;
		obj.ZespolyStore.setFirmaOddzial(firma_id,firma_oddzial_id);
	},
	setLiderId : function(lider_id){
		var obj = this;
		if(obj.lider_id !== lider_id){
			obj.lider_id = lider_id;
			obj.ZespolyStore.setLiderId(lider_id);
			if(obj.lider_id === 0){
				obj.setDisabled(true);
			}else{
				obj.enable();
			}
		}
	}
});
