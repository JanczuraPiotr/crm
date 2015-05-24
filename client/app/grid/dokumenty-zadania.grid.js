/**
 * Lista dokumentów wymaganych przez produkt sprzedwanyn w tym zadaniu i daty ich dostarczenia
 *
 * namespace client\app\grid
 * use client\app\store\DokumentyZadaniaStore
 * 
 * @confirm 2014-12-22
 */
Ext.define('DokumentyZadaniaGrid',{
	extend : 'Ext.grid.Panel',
	title : '',
//
	constructor : function(){
		var def = this;
		def.nr_zadania = -1;
		def.DokumentyZadaniaStore = new Ext.create('DokumentyZadaniaStore');
		def.callParent(arguments);
	},

	initComponent : function(){
		var def = this;

		Ext.apply(def,{
			pageSize : 10,
			store : def.DokumentyZadaniaStore,

			columns:[
				{
					xtype : 'datecolumn',
					text : 'dostarczono',
					dataIndex : 'data_dostarczenia',
					width : 85,
					renderer : Ext.util.Format.dateRenderer('Y-m-d'),
					editor : {
						xtype : 'datefield',
						format : 'Y-m-d',
						allowBlank : false
					}
				},{
					text : 'symbol',
					dataIndex : 'slownik_symbol',
					width : 80
				},{
					text : 'nazwa',
					dataIndex : 'slownik_nazwa',
					width : 200
				}
			], // columns
			bbar : [
				{
					xtype: 'pagingtoolbar',
					dock: 'bottom',
					store : def.DokumentyZadaniaStore,
					displayInfo: false
				}
			], // bbar
			plugins : // @test 2014-12-22
					 (function(){
											switch(CRM.user_status){
												case CRM.PRACOWNIK_KIEROWNIK:
												case CRM.PRACOWNIK_LIDER:
												case CRM.PRACOWNIK_ZWYKLY:
													return [
														{
															ptype : 'cellediting'
														}
													]
											}
									})()
		});
		def.callParent();
	},
	setNrZadania : function(nr_zadania){
		var def = this;
		if(def.nr_zadania !== nr_zadania){
			def.nr_zadania = nr_zadania;
			def.DokumentyZadaniaStore.setNrZadania(nr_zadania);
		}
	}
});
