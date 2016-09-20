<?php
/**
 * @package crmsw
 * @subpackage logic
 * @author Piotr Janczura <piotr@janczura.pl>
 * @task 2014-10-20 Przebudowa Table do obsługi zapytań preparowanych
 * @task 2014-10-20 Duża przebudowa DependencyTableRecord
 * @todo Kasowanie rekordu zablokowanego kluczem podrzędnym powinna ustawić data_do na null
 * @todo \pjpl\a\BusinessLogic <<< \crmsw\lib\a\BusinessLogic z usunięciem dziesdziczenia po klasie ...\beta\BusinessLogic
 */
class L_ZarzadDelete extends \crmsw\lib\a\BusinessLogic{
  public function __construct() {
    parent::__construct();
    $this->ZarzadyTable = $this->DB->tableZarzady();
  }
  protected function logic() {
    foreach ($this->dataIn['row'] as $key => $row) {
      try{
        $this->ZarzadyTable->getRecord($row['id'])->softDelete($row['data_do']);
        $this->dataOut[$key] = array('success'=>'true');
      }  catch (Exception $E){
        $this->dataOut[$key] = array('success'=>'false');
        $this->catchLogicException($E);
      }
    }
  }

  public function fromRequest(&$_request) {
    if(isset($_request['data'])){
      foreach ($_request['data'] as $key => $value) {
        $row = array();
        if(isset($value['id'])){
          $row['id'] = $this->Firewall->int($value['id']);
        }  else {
          $row['id'] = 0;
        }
        if(isset($value['data_do'])){
          $row['data_do'] = $this->Firewall->date($value['data_do']);
        }  else {
          $row['data_do'] = date('Y-m-d');
        }
        $this->dataIn[$key] = $row;
      }
    }
  }
  /**
   * @var ZarzadyTable
   */
  protected $ZarzadyTable;

}