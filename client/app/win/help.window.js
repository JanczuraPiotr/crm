/**
 * @confirm 2014-09-30
 */
 var HelpWindowSingleton = null;
Ext.define('HelpWindow',{
	extend : 'Ext.window.Window',
	title : 'Plik pomocy',
	autoShow : true,
	modale : false,
	height : 600,
	width : 700,

	constructor : function(user){
		var def = this;
		def.user = user;

		if(HelpWindowSingleton !== null){
			HelpWindowSingleton.show();
			return;
		}
		HelpWindowSingleton = def;
		def.callParent(arguments);
	},

	initComponent : function(){
		var def = this;
		Ext.apply(def,{
			items : {
				xtype : 'box',
				autoEl : {
					tag : 'iframe',
					style : 'height: 100%; width: 100%',
					src : 'help/pomoc-'+def.user+'.pdf'
				}
			}
		});
		def.callParent();
	},

	listeners : {
		close : function(){
			HelpWindowSingleton = null;
		}
	}

});

