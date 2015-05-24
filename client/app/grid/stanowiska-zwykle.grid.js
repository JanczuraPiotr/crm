/**
 * @prace 2014-09-24 extjs 4.2.2 >> extjs 5.0.1
 */
Ext.define('StanowiskaZwykleGrid',{
	extend : 'Ext.grid.Panel',
	xtype : 'stanowiska-zwykle-grid',

	constructor : function(){
		var thisSLG = this;

		thisSLG.firma_id = -1;
		thisSLG.firma_oddzial_id = -1;

		thisSLG.StanowiskaZwykleStore = new Ext.create('StanowiskaZwykleStore');

		thisSLG.RowExpander = new Ext.create('Ext.grid.plugin.RowExpander',{
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


		Ext.apply(thisSLG,{
//				pageSize : 10,
				width : 500,
				height : 600,
				store : thisSLG.StanowiskaZwykleStore,
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
					}
				], // columns
				bbar : [
					{
						xtype: 'pagingtoolbar',
						dock: 'bottom',
						scope : thisSLG,
						store : thisSLG.StanowiskaZwykleStore,
						pageSize : 30,
						displayInfo: false
					}
				], // bbar
				plugins:[
					thisSLG.RowExpander
				]
		});

		thisSLG.superclass.constructor.call(thisSLG, arguments);

		thisSLG.view.on('expandbody', function(rowNode,recordStanowisko, expandRow) {
			if(recordStanowisko.data.pracownik_id > 0){
				thisSLG.pracownikInfo(rowNode, recordStanowisko, expandRow);
			}else{
				thisSLG.pracownikInfo(rowNode, recordStanowisko, expandRow);
			}
		});

		thisSLG.view.on('collapsebody',function(rowNode, recordStanowisko, expandRow){
			thisSLG.clearExpand(rowNode, recordStanowisko, expandRow);
		});

		thisSLG.setDisabled(true);
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
	setFirmaOddzial : function(firma_id, firma_oddzial_id){
		console.log('StanowiskaLiderowGrid::setFirmaOddzial');
		console.log('firma_id = '+firma_id);
		console.log('firma_oddzial_id = '+firma_oddzial_id);
		var thisSLG = this;
		thisSLG.firma_id = firma_id;
		thisSLG.firma_oddzial_id = firma_oddzial_id;
		thisSLG.StanowiskaZwykleStore.setFirmaOddzial(firma_id,firma_oddzial_id);
		if(firma_oddzial_id > 0){
			thisSLG.enable();
		}else{
			thisSLG.setDisabled(true);
		}
	}
});
