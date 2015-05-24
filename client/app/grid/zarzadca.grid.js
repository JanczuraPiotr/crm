/**
 * @prace 2014-09-24 extjs 4.2.2 >> extjs 5.0.1
 */
Ext.namespace('Zarzadca');

Ext.define('ZarzadcaGrid',{
  xtype: 'ZarzadcaGrid',
  extend: 'Ext.grid.Panel',
  columns:[
    {
      text: 'id',
      dataIndex: 'id'
    },{
      text: 'login',
      dataIndex: 'login'
    },{
      text:'tel',
      dataIndex: 'tel'
    },{
      text:'email',
      dataIndex: 'email'
    }
  ],
  autoShow:true,
  height: 500,
  width: 500,
  store: ZarzadcaStore
});