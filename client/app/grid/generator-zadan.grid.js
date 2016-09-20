/**
 * @task 4.2.0
 */
Ext.define('GeneratorZadanGrid',{
	extend : 'Ext.grid.Panel',
	xtype : 'generator-zadan-grid',

	constructor : function(){
	  var def = this;
		def.ProduktyStore = new Ext.create('ProduktyStore');
		def.callParent(arguments);
	},

	initComponent : function(){
		var def = this;
		Ext.apply(def,{
				pageSize : 10,
				store : def.ProduktyStore,
				disabled : true,
				columns:[
					{
						text : 'id',
						dataIndex : 'id',
						width : 30
					},{
						text : 'symbol',
						dataIndex : 'symbol',
						width : 50
					},{
						text : 'nazwa',
						dataIndex : 'nazwa',
						width : 150
					},{
						xtype : 'datecolumn',
						text : 'od kiedy',
						dataIndex : 'data_od',
						renderer : Ext.util.Format.dateRenderer('Y-m-d'),
						width : 85
					}
				], // columns
				bbar : [
					{
						xtype: 'pagingtoolbar',
						dock: 'bottom',
						store : def.ProduktyStore,
						pageSize : 30,
						displayMsg : '',
						displayInfo: true
					}
				], // bbar

				plugins:[
					{
						ptype : 'rowexpander',
						rowBodyTpl : [
							'<p>',
								'<b>Opis : </b>{opis}',
							'</p>'
						]
					}
				]

		});
		def.callParent();
	},

	setNazwa : function(nazwa){
		var def = this;
		def.setTitle('Prodykty banku : '+nazwa);
	},
	setBankId : function(bank_id){
		var def = this;
		def.ProduktyStore.setBankId(bank_id);
		def.bank_id = bank_id;
		if(bank_id > 0){
			def.enable();
		}else{
			def.setDisabled(true);
		}
	}
});

