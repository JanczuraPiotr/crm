/**
 * @task 4.2.0
 */
Ext.define('PochodzenieKlientowGrid',{
	extend : 'Ext.grid.Panel',
	xtype : 'pochodzenie-klientow-grid',

	initComponent : function(){
		console.log('PochodzenieKlientowGrid::initComponent');
		var def = this;

		def.PochodzenieKlientowStore = new Ext.create('PochodzenieKlientowStore');
		def.RowEditing = new Ext.create('Ext.grid.plugin.RowEditing',{
			clicksToMoveEditor: 1,
			autoCancel: false,
			errorSummary : false,
			listeners: {
				canceledit : function(editor, context, eOpts){
					if( (context.record.data.id === '' || context.record.data.id === 0 ) && context.record.data.symbol === ""){
						def.PochodzenieKlientowStore.remove(def.getView().getSelectionModel().getSelection()[0]);
					}
				}
			}
		});

		Ext.apply(def,{
				pageSize : 10,
				height : 400,
				width : 350,
				store : def.PochodzenieKlientowStore,
				columns:[
					{
						text : 'id',
						dataIndex : 'id',
						width : 30
					},{
						text : 'symbol',
						dataIndex : 'symbol',
						width : 80,
						editor : {

						}
					},{
						text : 'opis',
						dataIndex : 'opis',
						width : 220,
						editor : {

						}
					}
				], // columns
				fbar : [
					{
						text : 'dodaj',
						scope : def,
						handler : function(){
							var rec = new PochodzenieKlientowModel({symbol:'',opis:''});
							rec.set('tmpId' , Ext.id());
							def.PochodzenieKlientowStore.insert(0, rec);
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
								cm = 'Usuwasz wpis o dokumencie o symbolu : '+selection.data.symbol;
							}
							Ext.Msg.confirm('Próbujesz usunąć rekord :' , cm ,
								function(btn){
									if(btn === 'yes'){
										if (selection) {
											def.PochodzenieKlientowStore.remove(selection);
										}
									}
								}
							);
						}
					}
				], // fbar
				bbar : [
					{
						xtype: 'pagingtoolbar',
						dock: 'bottom',
						store : def.PochodzenieKlientowStore,
						pageSize : 30,
						displayInfo: false
					}
				], // bbar
			plugins:[
					def.RowEditing
			]
		});
		def.callParent();
	}
});
