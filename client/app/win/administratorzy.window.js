/**
 * @prace 2014-09-24 extjs 4.2.2 >> extjs 5.0.1
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