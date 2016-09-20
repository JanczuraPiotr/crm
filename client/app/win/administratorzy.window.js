/**
 * @task 4.2.0
 */
Ext.define('AdministratorzyWindow',{
  xtype : 'administratorzy-window',
  extend : 'Ext.window.Window',
  title : "Administratorzy",
	resizable : false,
	collapsible : true,
  items : [
    {
      xtype : 'admin-zwykly-grid'
    }
  ],
	listeners : {
		close : function(panel,eOpts){
			AdministratorzyWindow = null;
		}
	}
});

var AdministratorzyWindow = null; // @todo przepisać na singletona w konstruktorze