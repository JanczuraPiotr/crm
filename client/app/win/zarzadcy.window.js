/**
 * @work 2014-09-24 extjs 4.2.2 >> extjs 5.0.1
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

