/**
 * Okno edycji słownika dokumentów wymaganych w różnych zaniach.
 *
 * namespace client\app\window
 * use client\app\grid\SlownikDokumentowGrid
 * 
 * @confirm 2014-12-22
 */
Ext.define('SlownikDokumentowWindow',{
	xtype : 'slownik-dokumentow-window',
	extend : 'Ext.window.Window',
	title : 'Slownik Dokumentow',
	collapsible : true,

	constructor : function(){
		var def = this;

		def.SlownikDokumentowGrid = new Ext.create('SlownikDokumentowGrid');
		def.superclass.constructor.call(def, arguments);
	},

	initComponent : function(){
		var def = this;

		Ext.apply(def,{
			items : [
					def.SlownikDokumentowGrid
			],
			resizable : false
		});
		def.callParent();
	},
	listeners : {
		close : function(panel, eOpts){
			SlownikDokumentowWindow = null;
		}
	}
});

var SlownikDokumentowWindow = null; // @todo przepisać na singletona w konstruktorze