/**
 * @task 4.2.0
 */
Ext.define('AdminZwyklyGrid',{
  extend: 'Ext.grid.Panel',
  xtype: 'admin-zwykly-grid',
	store : Ext.create('AdminZwyklyStore'),
  pageSize : 15,
  height: 530,
  width: 500,

	initComponent : function(){
	  thisAZG = this;
		this.editing = Ext.create('Ext.grid.plugin.RowEditing',{
			clicksToMoveEditor: 1,
			autoCancel: false,
			listeners: {
				canceledit : function(editor, context, eOpts){
					if( (context.record.data.id === '' || context.record.data.id === 0 ) && context.record.data.login === "" && context.record.data.tel === "" && context.record.data.email === ""){
						thisAZG.store.remove(thisAZG.getView().getSelectionModel().getSelection()[0]);
					}
				}
			}
		});

		Ext.apply(this,{

			columns:[
				{
					name : 'id',
					text : 'id',
					dataIndex : 'id'
				},{
					text: 'login',
					dataIndex: 'login',
					filterable: true,
					editor: {
					}
				},{
					text:'tel',
					dataIndex: 'tel',
					editor: {
					}
				},{
					text:'email',
					dataIndex: 'email',
					vtype : 'email',
					flex : 1,
					editor: {
					}
				},{
//					xtype : "actioncolumn",
//					items : [
//						{
//							tooltip:"Edit",
//							text : 'edytuj hasło',
//							icon : 'ext/extjs/resources/images/default/button/btn.gif',
//							handler : function(grid,rowNum,colNum){
//								alert('asdfas');
//							}
//						}
//					],
//					editor:{
//
//					},
//					renderer: function (value, column, record) {
////						console.log('AdminZwyklyGrid::columns::renderer');
////						console.log(value);
////						console.log(column);
//						column.value = '';
////						console.log(column);
////						console.log(record);
//						if(record.data.id === undefined || record.data.id === 0){
//							return '';
//						}
//						var id = Ext.id();
//						Ext.defer(function () {
//							new Ext.Button({
//								renderTo: id,
//								text: 'edytuj hasło',
//								width: 75,
//								handler: function () {
//									var ZmianaHaslaWindow  = new Ext.create('ZmianaHaslaWindow');
//									ZmianaHaslaWindow.setLogin(record.data.login);
//									ZmianaHaslaWindow.setUserTyp('ADMIN_ZWYKLY');
//									ZmianaHaslaWindow.show();
//								}
//							});
//						}, 100);
//						return Ext.String.format('<div id="{0}"></div>', id);
//					}
				}
			], // columns

//			tbar : [
//				{
//					xtype : 'toolbar',
//					items : [
//
//					]
//				}
//			],//tbar

			bbar : [
				{
					xtype: 'pagingtoolbar',
					dock: 'bottom',
					store : thisAZG.store,//AdminZwyklyStore,
					pageSize : 30,
					displayInfo: true
				},
				{
					text : 'dodaj',
					scope : this,
					handler : this.onClickAdminAdd
				},{
					text : 'usuń',
					scope : this,
					itemId : 'delete',
					handler : this.onClickDelete
				}
			], // bbar

			plugins:[
					this.editing
			]
		}); // Ext.apply

		this.callParent();
		this.getSelectionModel().on('selectionchange', this.onSelectChange, this);
	},


	onHasloClick : function(){
		console.log('AdminZwyklyGrid::onHasloClick');
	},
	onClickAdminAdd: function(){
			var rec = new AdminZwyklyModel({
					login: '',
					tel: '',
					email: ''
			});
			rec.set('tmpId' , Ext.id());
			thisAZG.store.insert(0, rec);
			thisAZG.editing.startEdit(0,1);
	},
	onClickDelete: function(){
		var selection = this.getView().getSelectionModel().getSelection()[0];
		var cm = '';
		if (selection) {
			cm = 'Usuwasz admina o nazwie : '+selection.data.login;
		}
		Ext.Msg.confirm('Próbujesz usunąć rekord :' , cm ,
			function(btn){
				if(btn === 'yes'){
					if (selection) {
						thisAZG.store.remove(selection);
					}
				}
			}
		);
	},
	onSelectChange: function(selModel, selections){
			this.down('#delete').setDisabled(selections.length === 0);
	}


});