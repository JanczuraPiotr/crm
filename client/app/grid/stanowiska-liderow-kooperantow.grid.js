/**
 * @work 2014-09-24 extjs 4.2.2 >> extjs 5.0.1
 */
Ext.define('StanowiskaLiderowKooperantowGrid',{
	extend : 'Ext.grid.Panel',
	xtype : 'stanowiska-liderow-mini-grid',

	constructor : function(){
		var thisSLMG = this;

		thisSLMG.StanowiskaLiderowStore = new Ext.create('StanowiskaLiderowKooperantowStore');
		thisSLMG.StanowiskaLiderowStore.load();


		Ext.apply(thisSLMG,{
			pageSize : 10,
			height : 400,
			width : 230,
			store : thisSLMG.StanowiskaLiderowStore,
			columns : [
				{
					text : 'stanowisko',
					dataIndex : 'id',
					width : 120,
					renderer : function(value, metaData, record, row, col, store, gridView){
						return record.data.nazwa+'<br><b>'+record.data.pracownik+'<b/>';
					}
				},{
					text : 'telefon',
					dataIndex : 'stanowisko_tel',
					width : 80
				}
			]
		});

		thisSLMG.superclass.constructor.call(thisSLMG, arguments);
	}
});
