Ext.namespace('AdminZwykly');

var AdminZwyklyFields = [
		{
			name : 'id',
			type : 'int'
		},{
			name : 'login',
			type : 'string'
		},{
			name : 'tel',
			type : 'string'
		},{
			name : 'email',
			type : 'string'
		}
	];

Ext.define('AdminZwyklyModel',{
  extend: 'Ext.data.Model',
  fields: AdminZwyklyFields,
	idProperty : 'id'
});