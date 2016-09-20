/**
 * Wyświetla stanowiska z zatrudnionymi pracownikami na podległych stanowiskach.
 * Jeżeli ustawiona jest wartość zmiennej lider_id to wyszuka stanowiska podległe temu liderowi.
 * Jeżeli zmienna lider_id < 1 to po uznawane jest że chodzi o zespół pracownika z konta którego wykonywany jest test.
 * @task 4.2.0
 */
Ext.define('StanowiskaZespoluGrid',{
	extend : 'Ext.grid.Panel',
	xtype : 'stanowiska-zespolu-grid',


	constructor : function(){
//	initComponent : function(){
		var thisSZG = this;
		thisSZG.lider_id = -1;
		thisSZG.StanowiskaZespoluStore = new Ext.create('StanowiskaZespoluStore');
		thisSZG.StanowiskaZespoluStore.load();

		Ext.apply(thisSZG,{
			title : 'stanowiska zespołu',
			height : 400,
			width : 230,
			store : thisSZG.StanowiskaZespoluStore,
			columns : [
				{
					text : 'stanowisko',
					dataIndex : 'id',
					width : 120,
					renderer : function(value, metaData, record, row, col, store, gridView){
						return record.data.stanowisko_nazwa+'<br><b>'+record.data.pracownik_nazwisko+' '+record.data.pracownik_imie+'<b/>';
					}
				},{
					text : 'telefon',
					dataIndex : 'stanowisko_tel',
					width : 80
				}
			]
		});
//		thisSZG.callParent();
		thisSZG.superclass.constructor.call(thisSZG,arguments);
	},
	setLiderId : function(lider_id){
		var thisSZG = this;
		if(thisSZG.lider_id !== lider_id){
			thisSZG.lider_id = lider_id;
			thisSZG.StanowiskaZespoluStore.setLiderId(lider_id);
		}
	}

});