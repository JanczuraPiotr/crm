/**
 * @task 4.2.0
 */
Ext.define('ZarzadcyWindow',{
  xtype : 'ZarzadcaWindow',
  extend : 'Ext.window.Window',
  title : "Zarzadcy",
  items : [
    {
      xtype : 'ZarzadcaGrid'
    }
  ],
	listeners : {
		close : function(panel,eOpts){
			ZarzadcyWindow = null
		}
	}
});

var ZarzadcyWindow = null; // @todo przepisać na singletona w konstruktorze

