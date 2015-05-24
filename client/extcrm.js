Ext.override(Ext,{
//	extend : 'Ext',
	singletons : [],
	getInstance : function(classname, config){
		return Ext.create(classname,config);
	}
});