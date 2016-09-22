/**
 * @done 4.2.0
 */
Ext.define('GeneratorZadanGrid',{
	extend : 'Ext.grid.Panel',

	constructor : function(){
	  var def = this;
		def.bankId = -1;

		def.produktyStore = Ext.create('ProduktyStore');
		def.rowExpander = Ext.create('Ext.grid.plugin.RowExpander',{
			selectRowOnExpand : true,
			expandOnDblClick : true,
			expandOnEnter : true,
			rowBodyTpl : [
							'<p>',
								'<b>Opis : </b>{opis}',
							'</p>'
			]
		});
		def.plugins = [
			def.rowExpander
		];
		def.callParent(arguments);
	},

	initComponent : function(){
		var def = this;
		Ext.apply(def,{
				pageSize : 10,
				store : def.produktyStore,
				disabled : true,
				columns:[
					{
						text : 'id',
						dataIndex : 'id',
						width : 30,
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
						store : def.produktyStore,
						pageSize : 30,
						displayMsg : '',
						displayInfo: true
					}
				], // bbar

//				plugins:[
//					def.rowExpander
//				]

		});
		def.callParent();
	},

	setNazwa : function(nazwa){
		var def = this;
		def.setTitle('Produkty banku : '+nazwa);
	},
	setBankId : function(bankId){
		var def = this;
		def.produktyStore.setBankId(bankId);
		def.bankId = bankId;
		if(bankId > 0){
			def.enable();
		}else{
			def.setDisabled(true);
		}
	}
});

