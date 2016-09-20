/**
 * @done 2014-09-23
 * @task 4.2.0
 */
Ext.define('WiazanieFirmBankowGrid',{
	extend : 'Ext.grid.Panel',

	constructor : function(){
		var def = this;

		def.firma_id = -1;
		def.bank_id = -1;
		def.firma_oddzial_id = -1;
		def.bank_oddzial_id = -1;

		def.WiazanieFirmBankowStore = new Ext.create('WiazanieFirmBankowStore');

		Ext.apply(def,{
				width : 400,
				height : 600,
				title : 'Współpracujące banki',
				store : def.WiazanieFirmBankowStore,
				columns:[
					{
						text : 'symbol banku',
						dataIndex : 'bank_symbol',
						width : 80
					},{
						text : 'nazwa banku',
						dataIndex : 'bank_nazwa',
						width : 165
					},{
						text : 'data od',
						dataIndex : 'data_od',
						renderer : Ext.util.Format.dateRenderer('Y-m-d')
					}
				], // columns
				bbar : [
					{
						xtype: 'pagingtoolbar',
						dock: 'bottom',
						scope : def,
						store : def.WiazanieFirmBankowStore,
						pageSize : 30,
						displayInfo: false
					},{
						text : 'dodaj',
						handler : function(){
							var wobw = new Ext.create('WyborOddzialuBankuWindow',{
								firma_oddzial_id : def.firma_oddzial_id
							});
							wobw.wybranoOddzialBanku = function(bank_oddzial_id){
								def.bank_oddzial_id = bank_oddzial_id;

								var WyborDaty = new Ext.create('WyborDatyWindow');
								WyborDaty.title = 'Wybierz datę powiązania';
								WyborDaty.width = 200;
								WyborDaty.onWybranoDate = function(data){
									if(def.firma_oddzial_id > 0 && def.bank_oddzial_id){
										// Wstaw record powiazania i wymuś na store odświerzenie widoku
										var rec = new WiazanieFirmBankowModel({
											firma_oddzial_id : def.firma_oddzial_id,
											bank_oddzial_id : def.bank_oddzial_id,
											data_od : data
										});
										rec.set('tmpId' , Ext.id());
										def.WiazanieFirmBankowStore.insert(0, rec);
										def.WiazanieFirmBankowStore.save();
									}
								}
								WyborDaty.show();
							};
							wobw.show();
						}
					},{
						text : 'usuń',
						scope : def,
						itemId : 'delete',
						handler : function(){
							var selection = this.getView().getSelectionModel().getSelection()[0];
							var cm = '';
							if (selection) {
								cm = 'Usuwasz powiązanie z bankiem o nazwie : '+selection.data.bank_nazwa;
								Ext.Msg.confirm('Próbujesz usunąć rekord :' , cm ,
									function(btn){
										if(btn === 'yes'){
											var WyborDaty = new Ext.create('WyborDatyWindow');
											WyborDaty.title = 'Wybierz datę końca powiązania';
											WyborDaty.onWybranoDate = function(data){
												selection.data.data_do = data;
												selection.commit();
												def.WiazanieFirmBankowStore.remove(selection);
												def.WiazanieFirmBankowStore.save();
											}
											WyborDaty.show();
										}
									}
								);
							}
						}
					}
				], // bbar
				plugins:[
					{
						ptype : 'rowexpander',
						rowBodyTpl : [
							'<p>',
								'<b>Oddział banku : </b><hr>',
								'<b>symbol : </b>{bank_oddzial_symbol}<br>',
								'<b>nazwa : </b>{bank_oddzial_nazwa}<br>',
								'<b>miejscowość : </b>{bank_oddzial_miejscowosc}<br>',
								'<b>ulica : </b>{bank_oddzial_ul}<br>',
								'<b>nr budynku : </b>{bank_oddzial_nr_b}<br>',
								'<b>nr lokalu : </b>{bank_oddzial_lokalu}<br>',
							'</p>'
						]
					}
				]
		});

		def.superclass.constructor.call(def, arguments);
		//------------------------------------------------------------------------------
		def.getSelectionModel().on('selectionchange', def.onSelectChange, def);

		def.setDisabled(true);
	},


	listeners : {
		cellclick : function( This, record, item, index, e, eOpts ){
			console.log('WiazanieFirmBankowychGrid::itemclick');
		}
	},
	onSelectChange: function(selModel, selections){
		var def = this;
		def.down('#delete').setDisabled(selections.length === 0);
	},
	setFirmaOddzialNazwa : function(nazwa){
		var def = this;
		def.setTitle('Banki powiazane z : '+nazwa);
	},
	setFirmaOddzial : function(firma_id, firma_oddzial_id){
		var def = this;
		def.firma_id = firma_id;
		def.firma_oddzial_id = firma_oddzial_id;
		def.WiazanieFirmBankowStore.setFirmaOddzial(firma_id,firma_oddzial_id);
		if(firma_oddzial_id > 0){
			def.enable();
		}else{
			def.setDisabled(true);
			def.setTitle('Wybierz oddział firmy');
		}
	}
});
