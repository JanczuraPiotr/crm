/**
 * @task 4.2.0
 */
Ext.define('BankiOddzialyMinGrid',{
	extend : 'Ext.grid.Panel',
	xtype : 'banki-oddzialy-grid',

	constructor : function(){
		var thisBOMG = this;

		thisBOMG.bank_id = 0;
		thisBOMG.BankiOddzialyStore = new Ext.create('BankiOddzialyStore');

		Ext.apply(thisBOMG,{
				pageSize : 10,
				height : 600,
				width : 600,
				title : 'Oddziały banku',
				store : thisBOMG.BankiOddzialyStore,
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
						scope : thisBOMG,
						store : thisBOMG.BankiOddzialyStore,
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
		thisBOMG.superclass.constructor.call(thisBOMG, arguments);
	},

	setBankNazwa : function(nazwa){
		var thisBOMG = this;
		thisBOMG.setTitle('Oddziały banku : '+nazwa);
	},
	setBankId : function(bank_id){
		var thisBOMG = this;
		if(bank_id > 0){
			thisBOMG.bank_id = bank_id;
			thisBOMG.enable();
			thisBOMG.BankiOddzialyStore.clearFilter();
			thisBOMG.BankiOddzialyStore.setBankId(bank_id);
			thisBOMG.BankiOddzialyStore.filter('bank_id',bank_id);
			thisBOMG.BankiOddzialyStore.load();
		}else{
			thisBOMG.bank_id = 0;
			thisBOMG.BankiOddzialyStore.clearFilter();
			thisBOMG.BankiOddzialyStore.setBankId(bank_id)
			thisBOMG.BankiOddzialyStore.filter('bank_id',bank_id);
			thisBOMG.setDisabled(true);
			thisBOMG.setTitle('Wybierz bank');
//			thisFG.BankOddzialyStore.load();
		}
	}
});
