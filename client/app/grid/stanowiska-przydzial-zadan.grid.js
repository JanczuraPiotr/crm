/**
 * @task 4.2.0
 * @todo W menu lokalnym uruchomienie okna w którym widać wszystkie zadania przydzielone do tego stanowiska.
 */
Ext.define('StanowiskaPrzydzialZadanGrid',{
	extend : 'Ext.grid.Panel',

	constructor : function(){
		var def = this;

		def.firma_id = -1;
		def.placowka_id = -1;

		switch (CRM.user_status){
			case CRM.PRACOWNIK_KIEROWNIK:
			case CRM.PRACOWNIK_LIDER:
				def.StanowiskaStore = new Ext.create('StanowiskaPodleglejGrupyStore');
				break;
			default:
				def.StanowiskaStore = new Ext.create('StanowiskaStore');
		}

		def.StatusyStanowiskStore = new Ext.create('StatusStanowiskaStore');

		def.RowExpander = new Ext.create('Ext.grid.plugin.RowExpander',{
			selectRowOnExpand : true,
			expandOnDblClick : false,
			expandOnEnter : false,
			rowBodyTpl : [
				'<p>',
					'<div id="zadania-stanowiska-{id}">',
					'</div>',
				'</p>'
			]
		});

		Ext.apply(def,{
				pageSize : 10,
				title : 'Stanowiska pracy w oddziale',
				width : 300,
				height : 600,
				store : def.StanowiskaStore,
				columns : [
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
							return def.StatusyStanowiskStore.getById(value).get('symbol');
						}
					}
				], // columns
				bbar : [
					{
						xtype : 'pagingtoolbar',
						dock  : 'bottom',
						scope : def,
						store : def.StanowiskaStore,
						pageSize : 30,
						displayInfo: false
					}
				], // bbar
				plugins:[
					def.RowExpander
				]
		});

		def.callParent(arguments);
//		def.getSelectionModel().on('selectionchange', def.onSelectChange, def);

		def.view.on('expandbody', function(rowNode,recStanowisko, expandRow) {
			var view = new Ext.create('Ext.view.View',{
				autoShow : false,
				autoSctoll : true,
				overflowY : 'scroll',
				height : 300,
				id : 'zadania-stanowiska-view-'+recStanowisko.data.id,
				renderTo : 'zadania-stanowiska-'+recStanowisko.data.id,
				emptyText:'nie przydzielono zadań',
				store : new Ext.create('ZadaniaNaglowekStore'),//.filter('stanowisko_id',recStanowisko.data.id),
				itemSelector : 'div.zadanie',
				tpl : [
					'<tpl for=".">',
						'<div class="zadanie" id="zadanie-{id}">',
							'<hr>',
							'<table><tbody>',
							'<tr><td>data </td><td> <b>{data_next_step:date("Y-m-d")}</b></td></tr>',
							'<tr><td>produkt </td><td> <b>{produkt_nazwa}</b></td></tr>',
							'<tr><td>bank </td><td> <b>{bank_nazwa}</b></td></tr>',
							'<tr><td>klient </td><td> <b>{klient_nazwa}</b></td></tr>',
							'</tbody></table>',
						'</div>',
					'</tpl>'
				]
			});
			view.store.setStanowiskoId(recStanowisko.data.id);
		});
		def.view.on('collapsebody',function(rowNode, recStanowisko, expandRow){
			if(Ext.getCmp('zadania-stanowiska-view-'+recStanowisko.data.id)){
				Ext.getCmp('zadania-stanowiska-view-'+recStanowisko.data.id).destroy();
			}
		});

		def.setDisabled(true);
	},
//	onSelectChange: function(selModel, selections){
//		console.log('StanowiskaPrzydzialZadanGrid::onSlectChange()');
//	},
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
	},
	przydzielonoZadania : function(stanowisko_id, zadania){
		var def = this;
		var cmp = Ext.getCmp('zadania-stanowiska-view-'+stanowisko_id);
		if(cmp !== undefined){
			cmp.getStore().load();
		}
	}
});
