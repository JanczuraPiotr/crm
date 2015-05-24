/**
 * Grid edycji słownika dokumnetów znanych w całym systemie
 *
 * namespace client\app\grid
 * use client\app\store\SlownikDokumentowStore
 *
 * @confirm 2014-12-22
 */
Ext.define('SlownikDokumentowGrid',{
	extend : 'Ext.grid.Panel',
	xtype : 'slownik-dokumentow-grid',

	initComponent : function(){
		var def = this;

		def.SlownikDokumentowStore = new Ext.create('SlownikDokumentowStore');
		def.RowEditing = new Ext.create('Ext.grid.plugin.RowEditing',{
			clicksToMoveEditor: 1,
			autoCancel: false,
			errorSummary : false,
			listeners: {
				canceledit : function(editor, context, eOpts){
					if( (context.record.data.id === '' || context.record.data.id === 0 ) && context.record.data.symbol === ""){
						def.SlownikDokumentowStore.remove(def.getView().getSelectionModel().getSelection()[0]);
					}
				}
			}
		});

		Ext.apply(def,{
				pageSize : 10,
				height : 400,
				width : 350,
				store : def.SlownikDokumentowStore,
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
						text : 'nazwa',
						dataIndex : 'nazwa',
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
							var rec = new SlownikDokumentowModel({symbol:'',nazwa:''});
							rec.set('tmpId' , Ext.id());
							def.SlownikDokumentowStore.insert(0, rec);
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
								cm = 'Usuwasz wpis o dokumencie o nazwie : '+selection.data.nazwa;
							}
							Ext.Msg.confirm('Próbujesz usunąć rekord :' , cm ,
								function(btn){
									if(btn === 'yes'){
										if (selection) {
											def.SlownikDokumentowStore.remove(selection);
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
						store : def.SlownikDokumentowStore,
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
