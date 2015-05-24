/**
 * events :
 *	przydzielonozadanie
 *		parametry:
 *			int : identyfikator stanowiska do którego przydzielono zadania.
 *			[]  : identyfikatory przydzielonych zadań
 *
 * @confirm 2015-01-02 ExtJS 5.1.0
 */
Ext.define('PrzydzialZadanGrid',{
	extend : 'Ext.grid.Panel',
	title : 'naglówki zadań',

	constructor : function(){
		var def = this;
		def.height = 600;
		def.width = 415;
		def.stanowisko_id = -1;

		def.ZadaniaNaglowekPrzydzialStore = new Ext.create('ZadaniaNaglowekPrzydzialStore');
		def.SelectionModel = new Ext.create('Ext.selection.CheckboxModel',{

		});
		def.SelectionModel.setSelectionMode('SIMPLE');

		Ext.apply(def,{
				store : def.ZadaniaNaglowekPrzydzialStore,
				selModel : def.SelectionModel,
				columns:[
					{
						text : 'data kroku',
						dataIndex : 'data_next_step',
						width : 70,
						renderer : Ext.util.Format.dateRenderer('Y-m-d')
					},{
						text : 'produkt',
						width : 150,
						renderer : function(value, metaData, record, row, col, store, gridView){
							return	'produkt : <b>'+record.data.produkt_nazwa+'</b><br>'+
											'bank    : <i>'+record.data.bank_nazwa+'</i>'
							;
						}
					},{
						text : 'klient',
						width : 150,
						renderer : function(value, metaData, record, row, col, store, gridView){
							return	'<b>'+record.data.klient_nazwa+'</b><br>'+
											(record.data.unique_typ != null ? record.data.unique_typ+' : '+record.data.unique_value : '')+'</br>'+
											record.data.kod_poczt+' '+record.data.miejscowosc+'</br>'+
											record.data.ul+' '+record.data.nr_b+'/'+record.data.nr_l
							;
						}
					}
				], // columns
				bbar : [
					{
						xtype: 'pagingtoolbar',
						dock: 'bottom',
						store : def.ZadaniaNaglowekPrzydzialStore,
						pageSize : 30,
						displayMsg : '',
						displayInfo: false
					},{
						xtype : 'button',
						text : 'przydziel oznaczone',
						handler : function(p1,p2){
							var records;
							var zadania = [];

							if(def.stanowisko_id < 1){
								Ext.Msg.alert('Błąd !','Nie określono stanowiska, dla którego mają zostać przydzielone zadania');
							}else{
								var records = def.SelectionModel.getSelection();
								for (var record in records){
									zadania.push(records[record].data.nr_zadania);
								}
								Ext.Ajax.request({
									url : '../server/ajax/przydzial-zadan.php?action=create',
									params : {
										stanowisko_id : def.stanowisko_id,
										zadania : Ext.JSON.encode(zadania)
									},
									success : function(response){
										var resp = Ext.JSON.decode(response.responseText);
										if(resp.success === true){
											def.ZadaniaNaglowekPrzydzialStore.load();
										}

										def.fireEvent('przydzielonozadanie',def.stanowisko_id,resp.data);
									}
								});
							}
						}
					}
				] // bbar
		});
		def.callParent(arguments);
	},
	setStanowiskoId : function(stanowisko_id){
		var def = this;

		if(def.stanowisko_id !== stanowisko_id){
			def.stanowisko_id = stanowisko_id;
			def.getStore().setStanowiskoId(def.stanowisko_id);
		}
	}
});
