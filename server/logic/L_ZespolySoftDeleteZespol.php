<?php
/**
 * @package crmsw
 * @subpackage logic
 * @author Piotr Janczura <piotr@janczura.pl>
 * @prace 2014-10-20 Przebudowa Table do obsługi zapytań preparowanych
 * @prace 2014-10-20 Duża przebudowa DependencyTableRecord
 * @todo Kasowanie rekordu zablokowanego kluczem podrzędnym powinna ustawić data_do na null
 * @todo \pjpl\a\BusinessLogic <<< \crmsw\lib\a\BusinessLogic z usunięciem dziesdziczenia po klasie ...\beta\BusinessLogic
 */

class L_ZespolySoftDeleteZespol extends \crmsw\lib\a\BusinessLogic{
  public function __construct() {
    parent::__construct();
    $this->ZespolyTable = $this->DB->tableZespoly();
  }
  protected function logic() {
    foreach ($this->dataIn as $key => $row) {
      try{
        $this->ZespolyTable->getRecord($row['lider_id'])->softDelete($row['data_do']);
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
        if(isset($value['lider_id'])){
          $row['lider_id'] = $this->Firewall->int($value['lider_id']);
        }  else {
          $row['lider_id'] = 0;
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
   * @var ZespolyTable
   */
  protected $ZespolyTable;
}
