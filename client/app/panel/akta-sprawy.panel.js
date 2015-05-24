/**
 * Wyświetla kontrolki opisujące notatki o całym zadaniu, wymaganych i dostarczonych dokumentach i opis sprzedawanego produktu
 *
 * namespace client\app\panel
 * use client\app\view\AktaSprawyView
 * use client\app\grid\DokumentyZadaniaGrid
 * use client\app\model\ZadaniaOpisModel
 *
 * @confirm 2015-01-02 ExtJS 5.1.0
 */
Ext.define('AktaSprawyPanel',{
	extend : 'Ext.panel.Panel',
	height : 600,
	width : 400,

	constructor : function(config){
		var def = this;
		def.nr_zadania = -1;
		def.AktaSprawyView = new Ext.create('AktaSprawyView',{
			height : 400
		});
		def.AktaSprawyView.ZadaniaOpisStore.on('load',function( This, records, successful, eOpts ){
			console.log('AktaSprawyPanel->AktaSprawyView->store->on->load');
			def.AktaSprawyView.scrollBy(0,10000,true);
		});
		def.DokumentyZadaniaGrid = new Ext.create('DokumentyZadaniaGrid',{
			height : 300
		});
		def.WpisDoAktTextarea = new Ext.form.field.TextArea({
			width : 400,
			height : 135
		});
		def.OpisProduktu = new Ext.form.field.Display({

		});
		Ext.apply(def,{

			items : [
				{
					xtype : 'tabpanel',
					items : [
						{
							title : 'akta sprawy',
							items : [
								{
									xtype : 'fieldset',
									margin : 0,
									border : 0,
									padding : 0,
									layout : 'vbox',
									items : [
										def.AktaSprawyView,
										def.WpisDoAktTextarea
									]
								}
							],
							bbar : [
								{
									xtype : 'tbfill'
								},{
									xtype : 'button',
									text : 'wpisz do akt',
									handler : function(button){
										var cmp = def.WpisDoAktTextarea;
										var rec = new Ext.create('ZadaniaOpisModel');
										rec.set('tmpId',Ext.id());
										rec.set('nr_zadania',def.nr_zadania);
										rec.set('notatka',cmp.getValue());
										def.AktaSprawyView.ZadaniaOpisStore.add(rec);
										def.AktaSprawyView.scrollBy(0,10000,true);
										cmp.setValue('');
									}
								}
							]
						},{
							title : 'załaczniki',
							items : [
								def.DokumentyZadaniaGrid
							]
						},{
							title : 'opis produktu',
							items : [
								def.OpisProduktu
							]
						}
					]
				}
			]

		});
//		asp.callParent(config);
		def.superclass.constructor.call(def,config);
	},
	update : function(){
		var def = this;
		def.DokumentyZadaniaGrid.store.load();
		def.AktaSprawyView.store.load();
		def.AktaSprawyView.scrollBy(0,10000,true);
	},
	setNrZadania : function(nr_zadania){
		var def = this;
		if(def.nr_zadania !== nr_zadania){
			def.nr_zadania = nr_zadania;
			def.DokumentyZadaniaGrid.setNrZadania(nr_zadania);
			def.AktaSprawyView.setNrZadania(nr_zadania);
			if(def.nr_zadania > 0 ){
				def.enable();
			}else{
				def.setDisabled(true);
			}
		}
	},
	setOpisProduktu : function(opis){
		var def = this;
		def.OpisProduktu.setValue(opis);
	}
});