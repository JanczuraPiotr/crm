/**
 * @task 2014-10-30 Zamiana response.ret >>> response.code
 * @task 2014-10-30 Dodanie do response tablicy "err" informącej o błędach rozpoznanych indywidualnie dla każdej encji podczas przetwarzania przez BusinessLogic
 * @task 4.2.0
 */
var ZarzadcaStore = Ext.create('Ext.data.JsonStore',{
  model: 'ZarzadcaModel',
  proxy:{
    type: 'ajax',
    url:  "../server/ajax/zarzadcy.php",
    reader: 'json'
  },
  autoLoad: true,
  root: 'data'
});