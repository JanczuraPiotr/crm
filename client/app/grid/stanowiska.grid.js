/**
 * @work 2014-09-24 extjs 4.2.2 >> extjs 5.0.1
 * @err 2014-09-22 Nie działa tworzenie stanowisk pracy ze względu na błędy kluczy obcych
 * @err 2014-09-22 Nie działa wybór statusu stanowiska - przy tworzezniu zadania wcale, przy edycji istniejącego do zatwierdzenia
 */
Ext.define('StanowiskaGrid',{
	extend : 'Ext.grid.Panel',

	constructor : function(){
		console.log('StanowiskaGrid::constructor()');
		var def = this;

		def.firma_id = -1;
		def.placowka_id = -1;

		def.StanowiskaStore = new Ext.create('StanowiskaStore');
		def.StatusyStanowiskStore = new Ext.create('StatusStanowiskaStore');
		def.RowExpander = new Ext.create('Ext.grid.plugin.RowExpander',{
			selectRowOnExpand : true,
			expandOnDblClick : false,
			expandOnEnter : false,
			rowBodyTpl : [
				'<p>',
					'<div id="stanowisko-zatrudnienie-{id}">',
						'<div id="stanowisko-zatrudnienie-left-{id}" style="float:left;">',
						'</div>',
						'<div id="stanowisko-zatrudnienie-right-{id}" style="float:right;">',
						'</div>',
					'</div>',
				'</p>'
			]
		});
		def.RowEditing = new Ext.create('Ext.grid.plugin.RowEditing',{
			clicksToMoveEditor: 1,
			autoCancel: false,
			errorSummary : false,
			listeners: {
				canceledit : function(editor, context, eOpts){
					if( (context.record.get('id') === '' || context.record.get('id') === 0 ) && context.record.get('symbol') === ""){
						def.store.remove(def.getView().getSelectionModel().getSelection()[0]);
					}
				},
				beforeedit : function( editor, context, eOpts ){
					if(context.record.get('pracownik_id') === 0){
						context.record.set('pracownik_id','');
					}
					if(context.record.get('placowka_id') === 0){
						context.record.set('placowka_id','');
					}
					if(context.record.get('status_stanowiska_id') === 0){
						context.record.set('status_stanowiska_id','');
					}
				}
			}
		});
		def.StatusyStanowiskCombo = new Ext.create('Ext.form.field.ComboBox',{
			typeAhead: false,
			triggerAction: 'all',
			selectOnTab: true,
			store : def.StatusyStanowiskStore,
			displayField : 'symbol',
			valueField : 'id',
			allowBlank : false,
			valueNotFoundText : '',
			listClass: 'x-combo-list-small',
			listConfig : {
				getInnerTpl : function(){
					return '{symbol} : <br>{opis}';
				}
			}
		});

		def.callParent(arguments);

		def.getSelectionModel().on('selectionchange', def.onSelectChange, def);
		def.view.on('expandbody', function(rowNode,recordStanowisko, expandRow) {
			if(recordStanowisko.get('pracownik_id')){
				def.pracownikInfo(rowNode, recordStanowisko, expandRow);
				def.buttonZwolnij(rowNode, recordStanowisko, expandRow);
			}else{
				def.pracownikInfo(rowNode, recordStanowisko, expandRow);
				def.buttonZatrudnij(rowNode, recordStanowisko, expandRow);
			}
		});
		def.view.on('collapsebody',function(rowNode, recordStanowisko, expandRow){
			def.clearExpand(rowNode, recordStanowisko, expandRow);
		});
		def.setDisabled(true);
	},
	initComponent : function(){
		console.log('StanowiskoGrid::initComponent()');
		var def = this;
		Ext.apply(def,{
				pageSize : 10,
				title : 'Oddziały firmy',
				width : 700,
				height : 600,
				store : def.StanowiskaStore,
				columns:[
					{
						text : 'symbol',
						dataIndex : 'symbol',
						width : 80,
						editor : {
							xtype : 'textfield',
							allowBlank : false
						}
					},{
						text : 'nazwa',
						dataIndex : 'nazwa',
						width : 120,
						editor : {
							xtype : 'textfield',
							allowBlank : false
						},
						renderer : function(value, metaData, record, row, col, store, gridView){
							if( record.get('pracownik') ){
								return value+'<br> <i>['+record.data.pracownik+']</i>';
							}else{
								return value+'<br> <i>[Wakat]</i>';
							}
						}
					},{
						text : 'status',
						dataIndex : 'status_stanowiska_id',
						width : 100,
						editor : def.StatusyStanowiskCombo,
						renderer : function(value){
							var record =  def.StatusyStanowiskCombo.findRecord(def.StatusyStanowiskCombo.valueField, value);
              return record ? record.get(def.StatusyStanowiskCombo.displayField) : def.StatusyStanowiskCombo.valueNotFoundText;
						}
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
				], // columns
				bbar : [
					{
						xtype: 'pagingtoolbar',
						dock: 'bottom',
						scope : def,
						store : def.StanowiskaStore,
						pageSize : 30,
						displayInfo: true
					},{
						text : 'dodaj',
						handler : function(){
								var rec = new StanowiskaModel({
									symbol : null,
									nazwa : null,
									placowka_id : def.placowka_id,
									pracownik_id : null,
									tel : null,
									email : null,
									status_stanowiska_id : null,
									data_od : null,
									data_do : null
								});
								rec.set('tmpId' , Ext.id());
								def.StanowiskaStore.insert(0, rec);
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
											def.StanowiskaStore.remove(selection);
										}
									}
								}
							);
						}
					}
				], // bbar
				plugins:[
					def.RowEditing,
					def.RowExpander
				]
		});
		def.callParent();
	},
	buttonZatrudnij : function(rowNode,recordStanowisko, expandRow){
		var def = this;
		return new Ext.create('Ext.Button',{
						text : 'przypisz pracownika',
						id : 'stanowisko-zatrudnienie-button-'+recordStanowisko.data.id,
						renderTo : 'stanowisko-zatrudnienie-right-'+recordStanowisko.data.id,
						handler : function(p1,p2){
							var pzw = new Ext.create('ZatrudnianieWindow',{
								onZatrudniono : function(recordZatrudnienie){
									console.log('StanowiskoGrid::onZatrudnono');
									def.clearExpand(rowNode, recordStanowisko, expandRow);
									var rec = def.StanowiskaStore.getById(recordStanowisko.data.id);
									rec.set('pracownik_id',recordZatrudnienie.data.pracownik_id);
									rec.set('pracownik',recordZatrudnienie.data.nazwisko+' '+recordZatrudnienie.data.imie);
									rec.set('pesel',recordZatrudnienie.data.pesel);
									rec.commit();
									def.pracownikInfo(rowNode,recordStanowisko, expandRow);
									def.buttonZwolnij(rowNode,recordStanowisko, expandRow);
								}
								,onNieZatrudniono : function(recordZatrudnienie){
									console.log('StanowiskoGrid::onNieZatrudnono');
									console.log(recordZatrudnienie);
								}
							});
							pzw.setStanowisko(def.firma_id,def.placowka_id,recordStanowisko.data.id);
							pzw.show();
						}
		});
	},
	buttonZwolnij : function(rowNode,recordStanowisko, expandRow){
		var def = this;
		return new Ext.create('Ext.Button',{
						text : 'Zwolnij pracownika',
						id : 'stanowisko-zatrudnienie-button-'+recordStanowisko.data.id,
						renderTo : 'stanowisko-zatrudnienie-right-'+recordStanowisko.data.id,
						handler : function(p1,p2){
							var winDataZwolnienia = Ext.create('Ext.window.Window',{
								title : 'Data zwolnienia',
								autoShow : true,
								modal : true,
								items : [
									{
										xtype : 'form',
										items : [
											{
												xtype : 'datefield',
												text : 'data',
												name : 'data',
												validateBlank : true,
												validateOnBlur : true,
												allowBlank : false,
												blankText : 'Musisz podać datę zwolnienia',
												format : 'Y-m-d'
											}
										]
									}
								],
								dockedItems : [
									{
										xtype : 'toolbar',
										dock : 'bottom',
										items: [
											{
												xtype : 'button',
												text : 'zwolnij',
												itemId : 'submit',
												formBind : true,
												handler : function(btn){
													console.log('StanowiskaGrid::buttonZwolnij::handler');
													var Form = btn.up('window').down('form').getForm();
													if(Form.isValid()){
														Ext.Ajax.request({
															url : '../server/ajax/zatrudnianie.php',
															params : {
																action : 'zwolnij',
																stanowisko_id : recordStanowisko.data.id,
																data_zwol : Form.getValues()['data']
															},
															success : function(response){
																console.log('StanowiskaGrid::buttonZwolnik::success');
																var resp = Ext.decode(response.responseText);
																if(resp.success === true){
																	var rec = def.StanowiskaStore.getById(recordStanowisko.data.id);
																	rec.set('pracownik_id',null);
																	rec.set('pracownik',null);
																	rec.set('pesel',null);
																	rec.commit();
																	winDataZwolnienia.close();
																	def.clearExpand(rowNode, recordStanowisko, expandRow);
																	def.pracownikInfo(rowNode, recordStanowisko, expandRow);
																	def.buttonZatrudnij(rowNode, recordStanowisko, expandRow);
																}
															}
														}); // Ext.Ajax.request
													}
												}
											},{
												xtype : 'button',
												text : 'anuluj',
												itemId : 'cancel',
												handler : function(btn){
													winDataZwolnienia.close();
												}
											}
										]
									}
								]
							});
						}
		});
	},
	clearExpand : function(rowNode,recordStanowisko, expandRow){
		if(Ext.getCmp('stanowisko-zatrudnienie-info-'+recordStanowisko.data.id)){
			Ext.getCmp('stanowisko-zatrudnienie-info-'+recordStanowisko.data.id).destroy();
		}
		if(Ext.getCmp('stanowisko-zatrudnienie-button-'+recordStanowisko.data.id)){
			Ext.getCmp('stanowisko-zatrudnienie-button-'+recordStanowisko.data.id).destroy();
		}
	},
	pracownikInfo : function(rowNode,recordStanowisko, expandRow){
		console.log('StanowiskaGrid::pracownikInfo()');
		console.log(rowNode);
		console.log(recordStanowisko);
		console.log(expandRow);
		if(recordStanowisko.data.pracownik_id > 0){
			return new Ext.create('Ext.Component',{
					id : 'stanowisko-zatrudnienie-info-'+recordStanowisko.data.id,
					renderTo : 'stanowisko-zatrudnienie-left-'+recordStanowisko.data.id,
					html : '<p><b>Pracownik : </b> <i>'+recordStanowisko.data.pracownik+'  [ pesel : '+recordStanowisko.data.pesel+' ] </i></p>'
			});
		}else{
			return new Ext.create('Ext.Component',{
					id : 'stanowisko-zatrudnienie-info-'+recordStanowisko.data.id,
					renderTo : 'stanowisko-zatrudnienie-left-'+recordStanowisko.data.id,
					html : '<p><b>Wakat</b></p>'
			});
		}
	},
	onSelectChange: function(selModel, selections){
		var def = this;
		def.down('#delete').setDisabled(selections.length === 0);
	},
	setPlacowkaNazwa : function(nazwa){
		var def = this;
		def.setTitle('Stanowisko pracy w oddziale : '+nazwa);
	},
	setPlacowka : function(firma_id, placowka_id){
		var def = this;
		def.firma_id = firma_id;
		def.placowka_id = placowka_id;
		def.StanowiskaStore.setPlacowkaId(placowka_id);
		if(placowka_id > 0){
			def.enable();
		}else{
			def.setDisabled(true);
			def.setTitle('Wybierz oddział firmy');
		}
	}
});
