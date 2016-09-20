/**
 * @task 4.2.0
 */
Ext.override(Ext,{
//	extend : 'Ext',
	singletons : [],
	getInstance : function(classname, config){
		return Ext.create(classname,config);
	}
});