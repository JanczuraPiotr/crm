/**
 * Nagłówki zadań. W praktyce rekordy zebrane na podstawie ostatnich kroków zadań.
 * @task 4.2.0
 * @done 2014-09-04 Zmiana znaczenia dat i sposobu notowania wykonywanych kroków.
 * @done 2014-08-28
 */
Ext.define('ZadaniaNaglowekStore',{
	extend : 'Ext.data.Store',
	model : ZadaniaNaglowekModel,
	autoLoad : false,
	autoSync : false,
	autoSave : false, // tylko podgląd
	remoteFilter : true,
	pageSize : 20,
	idProperty : 'id',
	klient_id : 0,
	stanowisko_id : 0,

	constructor : function(config){
		var def = this;
		var recKlient =  config  &&  ( config.recKlient || null );

		// @todo Do grida z nagłówkami zadań dodać menu lokalne w którym wywoływać okno z zadaniami w trakcie dla klienta na którym wywołano menu lokalne

		def.callParent(arguments);

		if( recKlient ){
			def.filterKlient = {
				'property' : 'klient_id',
				'value'    : recKlient.get('id'),
				"operator" : "="
			}
			def.filter(def.filterKlient);
		}
	},
	proxy : {
		type : 'ajax',
		method : 'POST',
		api : {
			read : '../server/ajax/naglowki-zadan.php?action=read'
		},
		reader : {
			type : 'json',
			rootProperty : 'data',
			totalProperty : 'countTotal'
		}
	},
	setNrZadania : function(nr_zadania){
		var def = this;
		var filter = [];
		if(def.nr_zadania !== nr_zadania){
			def.nr_zadania = nr_zadania;
			def.clearFilter();
			if(def.nr_zadania > 0){
				filter.push({
					property : 'nr_zadania',
					operator : '=',
					value    : def.nr_zadania
				})
			}
			if(def.klient_id > 0){
				filter.push({
						property : 'klient_id',
						value    : def.klient_id,
						operator : '='
				});
			}
			if(def.stanowisko_id > 0){
				filter.push({
					property : 'stanowisko_id',
					value    : def.stanowisko_id,
					operator : '='
				});
			}
		}
	},
	setKlientId : function(klient_id){
		var def = this;
		var filter = [];

		if(def.klient_id !== klient_id){
			def.klient_id = klient_id;
			def.clearFilter(true);
			if(def.klient_id > 0){
				filter.push({
						property : 'klient_id',
						value    : def.klient_id,
						operator : '='
				});
			}
			if(def.stanowisko_id > 0){
				filter.push({
					property : 'stanowisko_id',
					value    : def.stanowisko_id,
					operator : '='
				});
			}
			def.filter(filter);
		}
	},
	setStanowiskoId : function(stanowisko_id){
		var def  = this;
		var filter = [];
		if(def.stanowisko_id !== stanowisko_id){
			def.stanowisko_id = stanowisko_id;
			def.clearFilter(true);
			if(def.stanowisko_id > 0){
				filter.push({
					property : 'stanowisko_id',
					value    : def.stanowisko_id,
					operator : '='
				});
				def.filter(filter);
			}else{
				def.filter(def.filterKlient);
			}
		}
	}
});