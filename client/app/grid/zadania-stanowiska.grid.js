/**
 * @work 2014-09-24 extjs 4.2.2 >> extjs 5.0.1
 */
Ext.define('ZadaniaStanowiskaGrid',{
	extend : 'Ext.grid.Panel',
	xtype : 'zadania-stanowiska-grid',

	constructor : function(){
		var thisSMG = this;

		thisSMG.firma_id = -1;
		thisSMG.placowka_id = -1;

		thisSMG.StanowiskaStore = new Ext.create('StanowiskaStore');
		thisSMG.StatusyStanowiskStore = new Ext.create('StatusStanowiskaStore');

		thisSMG.RowExpander = new Ext.create('Ext.grid.plugin.RowExpander',{
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

		thisSMG.RowEditing = new Ext.create('Ext.grid.plugin.RowEditing',{
			clicksToMoveEditor: 1,
			autoCancel: false,
			errorSummary : false,
			listeners: {
				canceledit : function(editor, context, eOpts){
					console.log('ZadaniaStanowiskaGrid::editing::canceledit');
					if( (context.record.data.id === '' || context.record.data.id === 0 ) && context.record.data.symbol === ""){
						thisSMG.store.remove(thisSMG.getView().getSelectionModel().getSelection()[0]);
					}
				},
				beforeedit : function( editor, context, eOpts ){
					console.log(context.record);
					if(context.record.data.pracownik_id === 0){
						context.record.data.pracownik_id = '';
					}
					if(context.record.data.placowka_id === 0){
						context.record.data.placowka_id = '';
					}
					if(context.record.data.status_stanowiska_id === 0){
						context.record.data.status_stanowiska_id = '';
					}
				}
			}
		});

		Ext.apply(thisSMG,{
				pageSize : 10,
				title : 'Oddziały firmy',
				width : 300,
				height : 600,
				store : thisSMG.StanowiskaStore,
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
							if(record.data.pracownik.length > 0){
								return value+'<br> <i>['+record.data.pracownik+']</i>';
							}else{
								return value+'<br> <i>[Wakat]</i>';
							}
						}
					},{
						text : 'status',
						dataIndex : 'status_stanowiska_id',
						width : 40,
						renderer : function(value){
							return thisSMG.StatusyStanowiskStore.getById(value).get('symbol');
						}
					}
				], // columns
				bbar : [
					{
						xtype: 'pagingtoolbar',
						dock: 'bottom',
						scope : thisSMG,
						store : thisSMG.StanowiskaStore,
						pageSize : 30,
						displayInfo: false
					}
				], // bbar
				plugins:[
					thisSMG.RowEditing,
					thisSMG.RowExpander
				]
		});

		thisSMG.superclass.constructor.call(thisSMG, arguments);

		thisSMG.getSelectionModel().on('selectionchange', thisSMG.onSelectChange, thisSMG);

		thisSMG.view.on('expandbody', function(rowNode,recordStanowisko, expandRow) {
		});

		thisSMG.view.on('collapsebody',function(rowNode, recordStanowisko, expandRow){
		});

		thisSMG.setDisabled(true);
	},
	onSelectChange: function(selModel, selections){
		var thisSG = this;
	},
	setPlacowkaNazwa : function(nazwa){
		var thisSG = this;
		thisSG.setTitle('Stanowisko pracy w oddziale : '+nazwa);
	},
	setPlacowka : function(firma_id, placowka_id){
		var thisSG = this;
		thisSG.firma_id = firma_id;
		thisSG.placowka_id = placowka_id;
		thisSG.StanowiskaStore.setPlacowkaId(placowka_id);
		if(placowka_id > 0){
			thisSG.enable();
		}else{
			thisSG.setDisabled(true);
			thisSG.setTitle('Wybierz oddział firmy');
		}
	}
});
