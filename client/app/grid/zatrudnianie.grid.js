/**
 * @task 4.2.0
 */
Ext.define('ZatrudnianieGrid',{
	extend : 'Ext.grid.Panel',

	constructor : function(){
		console.log('ZatrudnianieGrid::constructor');
		var def = this;

		def.ZatrudnianieStore = new Ext.create('ZatrudnianieStore');
		def.ZatrudnianieStore.on('przypisanodostanowiska',function(record){
			def.fireEvent('przypisanodostanowiska',record);
			return true;
		});
		def.CellEditing = new	Ext.create('Ext.grid.plugin.CellEditing', {
				clicksToEdit: 1,
				listeners : {
					edit : function(editor, e, eOpts){
						e.record.commit();
					},
					canceledit : function(editor, e, eOpts){
						e.record.commit();
					}
				}
		});


		def.callParent(arguments); // def.superclass.constructor.call(def,arguments);
		def.enableBubble('przypisanodostanowiska');
		def.firma_id = -1;
		def.on('przypisanodostanowiska',def.onZatrudniono,def);


//		thisZG.height = 600;
//		thisZG.width = 200;
//		thisZG.callParent();

	},
	initComponent : function(){
		var def = this;

		Ext.apply(def,{
				pageSize : 10,
				height : 300,
				width : 370,
				store : def.ZatrudnianieStore,
				columns:[
					{
						text : 'nazwisko',
						dataIndex : 'nazwisko',
						width : 100
					},{
						text : 'imie',
						dataIndex : 'imie',
						width : 80
					},{
						text : 'pesel',
						dataIndex : 'pesel',
						width : 80
					},{
						text : 'data zatrudnienia',
						dataIndex : 'data_od',
						xtype : 'datecolumn',
						format: 'Y-m-d',
						editor : {
							xtype : 'datefield',
							'default' : null,
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
						store : def.ZatrudnianieStore,
						pageSize : 30,
						displayInfo: true
					}
				],
				plugins : [
					def.CellEditing
				],
				listeners : {
					celldblclick : function( This, td, cellIndex, record, tr, rowIndex, e, eOpts ){
						console.log('ZatrudnianieGrid::celldblclick');
						console.log(record.data);
						if(!record.data.data_od){
							Ext.MessageBox.alert('','Podaj datę zatrudnienia');
							return false;
						}
						def.onWybranoPracownika(record.data.pracownik_id);
						def.ZatrudnianieStore.sync();
						return true;
					},

//					edit : function(editor, e, eOpts){
//						console.log('PracownicyZatrudnienieGrid::edit');
//						console.log(editor);
//						console.log(e);
//						e.grid.store.save();
//					},
//					canceledit : function(editor, e, eOpts){
//						console.log('PracownicyZatrudnienieGrid::canceledit');
//						console.log(editor);
//						console.log(e);
//						e.record.commit();
//					}
				}
		});
		def.callParent();
	},
	setStanowisko : function(firma_id,placowka_id,stanowisko_id,data_od){
		var def = this;
		def.firma_id = firma_id;
		def.placowka_id = placowka_id;
		def.stanowisko_id = stanowisko_id;
		def.data_od = data_od;
		def.ZatrudnianieStore.setStanowisko(firma_id,placowka_id,stanowisko_id,data_od);
	},
	czyZatrudniono : function(){
		var def = this;
		return def.store.czyZatrudniono();
	},
	onZatrudniono : function(record){
			return true;
	},
	onNieZatrudniono : function(pracownik_id){
		console.log('ZatrudnianieGrid::onNieZatrudniono -- własne');
	},
	onWybranoPracownika : function(pracownik_id){
	}
});
