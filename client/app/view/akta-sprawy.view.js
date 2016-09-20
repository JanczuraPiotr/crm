/**
 * Wyświetla listę notatek tworzonych podczas przetwarzania zadania.
 *
 * namespace client\app\view
 * use client\app\store\ZadaniaOpiosStore
 *
 * @task 4.2.0
 */
Ext.define('AktaSprawyView',{
	extend : 'Ext.view.View',
	overflowY : 'scroll',
	autoSctoll : true,
	width : 395,
	height : 400,

	constructor : function(){
		var def = this;
		def.nr_zadania = 0;
		def.setNrZadania(def.nr_zadania);
		def.ZadaniaOpisStore = new Ext.create('ZadaniaOpisStore');
		def.callParent(arguments);
	},
	initComponent : function(){
		var def = this;

		Ext.apply(def,{
			store : def.ZadaniaOpisStore,
			itemSelector : 'div.zadanie',
			tpl : [
				'<tpl for=".">',
					'<div class="zadanie" id="zadanie-{id}">',
						'<b>{create:date("Y-m-d H:i:s")}</b><br>',
						'{notatka}',
						'<hr/>',
					'</div>',
				'</tpl>'
				]
		});

		def.callParent();
	},
	setNrZadania : function(nr_zadania){
		var def = this;
		if(def.nr_zadania !== nr_zadania){
			def.nr_zadania = nr_zadania;
			def.ZadaniaOpisStore.setNrZadania(nr_zadania);
		}
	}
});