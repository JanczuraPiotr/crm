/**
 * @work 4.2.0
 */

Ext.define('NadzorKlientowGrid',{
	extend : 'Ext.grid.Panel',
	xtype : 'nadzor-klientow-grid',
	width : 372,
//	height : 505,

	constructor : function(){
		var def = this;
		def.klient_id = 0;


		def.KlienciStore = Ext.create('KlienciStore');
		def.KlienciStore.pageSize = 24;
		def.KlienciStore.remoteFilter = true;

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
						console.log('NadzorKlientowGrid->ContextMenu->zadania procedowane');
					}
				},{
					text : 'zadania zakończone',
					handler : function(){
						console.log('NadzorKlientowGrid->ContextMenu->zadania zakonczone');
					}
				},{
					text : 'wszystkie zadania',
					handler : function(){
						console.log('NadzorKlientowGrid->ContextMenu->zadania wszystkie');
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
						text : 'coś1',
						scope :  def,
						handler : function(){
						}
					},{
						text : 'coś2',
						scope :  def,
						itemId : 'delete',
						handler : function(){
//							var selection = this.getView().getSelectionModel().getSelection()[0];
//							var cm = '';
//							if (selection) {
//								cm = 'Usuwasz klienta o nazwie : '+selection.data.nazwa;
//							}
//							Ext.Msg.confirm('Próbujesz usunąć rekord :' , cm ,
//								function(btn){
//									if(btn === 'yes'){
//										if (selection) {
//											 def.KlienciStore.remove(selection);
//										}
//									}
//								}
//							);
						}
					}
				] // bbar

		});
		def.callParent();
	}
});
