/**
 * @confirm 2015-01-01 ExtJS 5.1.0
 * @todo Dodać jakiś przycisk powodujący wyczyszczenie wszystkich pól edycyjnych filtra.
 * @todo Kolumny liczbowe wyrównać do prawej strony.
 * @todo Data następnej dostawy musi uwzględnić godziny
 */

Ext.define('KlienciGrid',{
	extend : 'Ext.grid.Panel',
	xtype : 'klienci-grid',
	width : 695,
	height : 505,

	constructor : function(){
		var def = this;
		def.klient_id = 0;
		def.KlienciStore = Ext.create('KlienciStore');
		def.KlienciStore.remoteFilter = true;
		def.PochodzenieKlientowStore = Ext.create('PochodzenieKlientowStore');
		def.PochodzenieKlientowStore.load();
		def.StatusKlientaStore = Ext.create('StatusKlientaStore');
		def.StatusKlientaStore.load();
		def.RowExpander = Ext.create('Ext.grid.plugin.RowExpander',{
			selectRowOnExpand : true,
			expandOnDblClick : true,
			expandOnEnter : true,
			rowBodyTpl : [
				'		<table>',
				'			<thead>',
				'				<tr><th width = "100px" ></th><th width = "100px" ></th><th></th><th></th></tr>',
				'			</thead>'+
				'			<tbody>',
				'				<tr><td> kod poczt:		</td><td> {kod_poczt}							</td><td> email :				</td><td> {email}			</td></tr>',
				'				<tr><td> miejscowość:	</td><td> {miejscowosc}						</td><td> tel kom :			</td><td> {telkom}		</td></tr>',
				'				<tr><td> ulica :			</td><td> {ul}										</td><td> tel dom :			</td><td> {teldom}		</td></tr>',
				'				<tr><td> nr budynku :	</td><td> {nr_b}									</td><td> tel praca :		</td><td> {telpraca}	</td></tr>',
				'				<tr><td> nr lokalu	:	</td><td> {nr_l}									</td><td>								</td><td>							</td></tr>',
				'				<tr><td> data od		:	</td><td>	{data_od:date("Y-m-d")}	</td><td> data do	:			</td><td> {data_do:date("Y-m-d")}</td></tr>',
				'			</tbody>',
				'		</table>'
			]
		});
		def.ActionColumn = new Ext.grid.column.Action({
			width : 20,
			items : [
				{
					icon : 'images/edit.png',
					tooltip : 'edytuj klienta',
					handler : function(grid, rowIndex, colIndex){
						var KlientForm = Ext.create('KlientForm',{/*config*/},def.getView().getRecord(rowIndex), function(rec){
							if(rec){
								rec.commit();
							}
						});
						KlientForm.show();
					}
				}
			]
		});

		def.ContextMenu = Ext.create('Ext.menu.Menu', {
			items: [
				{
					text : 'zadania procedowane',
					handler : function(){
						new Ext.create('ZadaniaProcedowaneKlientaWindow',{
							recKlient : def.getSelectionModel().getSelection()[0]
						});
					}
				},{
					text : 'zadania zakończone',
					handler : function(){
						console.log('KlienciGrid::ContextMenu::ZadaniaZakonczone()');
					}
				}
			]});

		def.store = def.KlienciStore;

		def.plugins = [
			def.RowExpander
		];

		def.callParent(arguments);
		def.RowsFilter = new RowsFilter(def);

		def.on('itemcontextmenu',function(view, record, item, index, event){
			var position = event.getXY();
			event.stopEvent();
			def.ContextMenu.showAt(position);
		});
	},

	initComponent : function(){
		var def = this;

		Ext.apply( def,{
				pageSize : 10,
				columns:[
					{
						text : 'id',
						dataIndex : 'id',
						width : 43,
						minWidth : 43,
						maxWidth : 43,
						editor : {
							xtype : 'textfield'
						},
						items : {
							xtype : 'textfield',
							listeners : {
								change : function( thet, newValue, oldValue, eOpts ){
									def.RowsFilter.change('id',newValue);
								}
							}
						}
					},{
						text : 'nazwa',
						dataIndex : 'nazwa',
						width : 100,
						minWidth : 100,
						maxWidth : 100,
						editor : {
							xtype : 'textfield',
							allowBlank : false
						},
						items : {
							xtype : 'textfield',
							listeners : {
								change : function( thet, newValue, oldValue, eOpts ){
									def.RowsFilter.change('nazwa',newValue);
								}
							}
						}
					},{
						text : 'imie',
						dataIndex : 'imie',
						width : 80,
						minWidth : 80,
						maxWidth : 80,
						editor : {
							xtype : 'textfield'
						},
						items : {
							xtype : 'textfield',
							listeners : {
								change : function( thet, newValue, oldValue, eOpts ){
									def.RowsFilter.change('imie',newValue);
								}
							}
						}
					},{
						text : 'pesel',
						dataIndex : 'pesel',
						width : 80,
						minWidth : 80,
						maxWidth : 80,
						'default' : null,
						editor : {
							'default' : null
						},
						items : {
							xtype : 'textfield',
							listeners : {
								change : function( thet, newValue, oldValue, eOpts ){
									def.RowsFilter.change('pesel',newValue);
								}
							}
						}
					},{
						text : 'nip',
						dataIndex : 'nip',
						width : 80,
						minWidth : 80,
						maxWidth : 80,
						'default' : null,
						editor : {
							'default' : null
						},
						items : {
							xtype : 'textfield',
							listeners : {
								change : function( thet, newValue, oldValue, eOpts ){
									def.RowsFilter.change('nip',newValue);
								}
							}
						}
					},{
						text : 'miejscowosc',
						dataIndex : 'miejscowosc',
						width : 80,
						minWidth : 80,
						maxWidth : 80,
						editor : {
							xtype : 'textfield'
						},
						items : {
							xtype : 'textfield',
							listeners : {
								change : function( thet, newValue, oldValue, eOpts ){
									def.RowsFilter.change('miejscowosc',newValue);
								}
							}
						}
					},{
						text : 'ulica',
						dataIndex : 'ul',
						width : 80,
						minWidth : 80,
						maxWidth : 80,
						'default' : null,
						editor : {
							xtype : 'textfield'
						},
						items : {
							xtype : 'textfield',
							listeners : {
								change : function( thet, newValue, oldValue, eOpts ){
									def.RowsFilter.change('ul',newValue);
								}
							}
						}
					},{
						text : 'nr bud',
						dataIndex : 'nr_b',
						width : 45,
						minWidth : 45,
						maxWidth : 45,
						editor : {
							xtype : 'textfield'
						},
						items : {
							xtype : 'textfield',
							listeners : {
								change : function( thet, newValue, oldValue, eOpts ){
									def.RowsFilter.change('nr_b',newValue);
								}
							}
						}
					},{
						text : 'nr lok',
						dataIndex : 'nr_l',
						width : 45,
						minWidth : 45,
						maxWidth : 45,
						editor : {
							xtype : 'textfield'
						},
						items : {
							xtype : 'textfield',
							listeners : {
								change : function( thet, newValue, oldValue, eOpts ){
									def.RowsFilter.change('nr_l',newValue);
								}
							}
						}
					},
					def.ActionColumn

				], // columns
				bbar : [
					{
						xtype: 'pagingtoolbar',
						dock: 'bottom',
						store :  def.KlienciStore,
						pageSize : 30,
						displayInfo: true
					},{
						text : 'dodaj',
						scope :  def,
						handler : function(){
							var recNew = new KlienciModel({
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
								telpraca:'',
								opis : '',
								pochodzenie_klientow_id : 0,
								firma_id : CRM.firma_id,
								data_od:'',
								data_do:''
							});
							var KlientForm = Ext.create('KlientForm',{},recNew,function(rec){
								if(rec === null){
									return;
								}
								rec.set('tmpId' , Ext.id());
								def.KlienciStore.insert(0, rec);
								// @todo Po dodaniu nowego rekordu grid musi być odświerzony by rekord można było usunąć. Edycja możliwa jest od razu
							});
							KlientForm.show();
						}
					},{
						text : 'usuń',
						scope :  def,
						itemId : 'delete',
						handler : function(){
							var selection = this.getView().getSelectionModel().getSelection()[0];
							var cm = '';
							if (selection) {
								cm = 'Usuwasz klienta o nazwie : '+selection.data.nazwa;
							}
							Ext.Msg.confirm('Próbujesz usunąć rekord :' , cm ,
								function(btn){
									if(btn === 'yes'){
										if (selection) {
											 def.KlienciStore.remove(selection);
										}
									}
								}
							);
						}
					}
				] // bbar

		});
		def.callParent();
	}
});
