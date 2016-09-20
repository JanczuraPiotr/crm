/**
 * Zadania do wykonania wobec jednego klienta.
 * Okno wyświetlane jest z menu kontekstowego okna edycji danych podstawowych klienta.
 * @done 2015-03-12 extjs 5.1.0
 * @done 2014-08-12
 */
var ZadaniaProcedowaneKlientaWindow = Ext.define('ZadaniaProcedowaneKlientaWindow',{
	extend : 'Ext.window.Window',
	autoShow : true,
	resizable : false,
	maximizable : false,
	collapsible : true,
	instance : {},

	constructor : function(config){
		var def = this,
				recKlient = config.recKlient;

		return (function(){
							def.klient_id = recKlient.get('id');
							if(def.instance[def.klient_id]){
								def.instance[def.klient_id].show().expand();
								return def.instance[def.klient_id];
							};
							def.title = 'Zadania w trakcie : '
											+  recKlient.get('nazwa')
											+( recKlient.get('imie').length > 0        ? ' '+recKlient.get('imie') : '' )
											+( recKlient.get('miejscowosc').length > 0 ? ', '+recKlient.get('miejscowosc') : '' )
											+( recKlient.get('ul').length > 0          ? ', ul. '+recKlient.get('ul') : '')
											+( recKlient.get('nr_b').length > 0        ? ' '+recKlient.get('nr_b') : '')
											+( recKlient.get('nr_l').length > 0        ? '/'+recKlient.get('nr_l') : '')
											+( recKlient.get('pesel').length > 0       ? ', pesel : '+recKlient.get('pesel') : '')
											+( recKlient.get('nip').length > 0         ? ', nip : '+recKlient.get('nip') : '')
											+( recKlient.get('telkom').length > 0      ? ', tel kom : '+recKlient.get('telkom') : '')
											+( recKlient.get('teldom').length > 0      ? ', tel dom : '+recKlient.get('teldom') : '')
											+( recKlient.get('telpraca').length > 0    ? ', tel praca: '+recKlient.get('telpraca') : '')
							;

							def.PanelZadan = new Ext.create('ZadaniaProcedowaneKlientaPanel',{
								recKlient : recKlient
							});
							def.callParent(arguments);
							def.instance[def.klient_id] = def;
							return def;
						})();
	},

	initComponent : function(){
		var def = this;

		Ext.apply(def,{
			layout : {
				type : 'hbox'
			},
			items : [
				def.PanelZadan
			]
		});

		def.callParent();
	},

	listeners : {
		close : function(def, eOpts){
			delete def.instance[def.klient_id];
		}
	}

});
