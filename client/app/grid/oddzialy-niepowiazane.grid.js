/**
 * @task 4.2.0
 */
Ext.define('OddzialyNiepowiazaneGrid',{
	extend : 'Ext.grid.Panel',
	xtype : 'oddzialy-niepowiazane-grid',

	constructor : function(){
		var thisONG = this;

		thisONG.bank_id = 0;
		thisONG.firma_oddzial_id = -1;
		thisONG.OddzialyNiepowiazaneStore = new Ext.create('OddzialyNiepowiazaneStore');

		Ext.apply(thisONG,{
				pageSize : 10,
				height : 600,
				width : 600,
				title : 'Oddziały banku',
				store : thisONG.OddzialyNiepowiazaneStore,
				columns:[
					{
						text : 'symbol',
						dataIndex : 'symbol',
						width : 50,
						editor : {
							xtype : 'textfield',
							allowBlank : false
						}
					},{
						text : 'nazwa',
						dataIndex : 'nazwa',
						width : 150,
						editor : {
							xtype : 'textfield',
							allowBlank : false
						}
					},{
						text : 'miejscowosc',
						dataIndex : 'miejscowosc',
						width : 130,
						editor : {}
					},{
						text : 'ulica',
						dataIndex : 'ul',
						width : 130,
						editor : {}
					},{
						text : 'nr bud.',
						dataIndex : 'nr_b',
						width : 50,
						editor : {}
					},{
						text : 'nr lok.',
						dataIndex : 'nr_l',
						width : 50,
						editor : {}
					}
				], // columns
				bbar : [
					{
						xtype: 'pagingtoolbar',
						dock: 'bottom',
						scope : thisONG,
						store : thisONG.OddzialyNiepowiazaneStore,
						pageSize : 30,
						displayInfo: false
					}
				], // bbar
				plugins : [
					{
						ptype : 'rowexpander',
						expandOnDblClick : false,
						rowBodyTpl : [
							'<p>',
								'<b>telefon : </b>{tel}<br>',
								'<b>email : </b>{email}<br>',
							'</p>'
						]
					}
				]
		});
		thisONG.superclass.constructor.call(thisONG, arguments);
	},

	setBankNazwa : function(nazwa){
		var thisONG = this;
		thisONG.setTitle('Oddziały banku : '+nazwa);
	},
	setBankId : function(bank_id){
		var thisONG = this;
		thisONG.OddzialyNiepowiazaneStore.setBankId(bank_id);
//
//		if(bank_id > 0){
//			thisBOMG.bank_id = bank_id;
//			thisBOMG.enable();
//			thisBOMG.OddzialyNiepowiazaneStore.clearFilter();
//			thisBOMG.OddzialyNiepowiazaneStore.setBankId(bank_id);
//			thisBOMG.OddzialyNiepowiazaneStore.filter('bank_id',bank_id);
//			thisBOMG.OddzialyNiepowiazaneStore.load();
//		}else{
//			thisBOMG.bank_id = 0;
//			thisBOMG.OddzialyNiepowiazaneStore.clearFilter();
//			thisBOMG.OddzialyNiepowiazaneStore.setBankId(bank_id)
//			thisBOMG.OddzialyNiepowiazaneStore.filter('bank_id',bank_id);
//			thisBOMG.setDisabled(true);
//			thisBOMG.setTitle('Wybierz bank');
////			thisFG.BankOddzialyStore.load();
//		}
	},
	setFirmaOddzialId : function(firma_oddzial_id){
		var thisONG = this;
		thisONG.OddzialyNiepowiazaneStore.setFirmaOddzialId(firma_oddzial_id);
	}
});
