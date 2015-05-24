<?php
/**
 * @package crmsw
 * @subpackage logic
 * @author Piotr Janczura <piotr@janczura.pl>
 * @prace 2014-10-20 Przebudowa Table do obsługi zapytań preparowanych
 * @prace 2014-10-20 Duża przebudowa DependencyTableRecord
 * @deprecated Zmiana lidera powinna odbywać się poprzez zmianę stanowiska w tabeli liderzy
 */
class L_ZespolyZmienLidera extends \crmsw\lib\a\BusinessLogic{
  public function __construct() {
    parent::__construct();
    $this->ZespolyTable = $this->DB->tableZespoly();
  }
  protected function logic() {
    foreach ($this->dataIn as $key => $row) {
      try {
        $Record = $this->ZespolyTable->getRecord($id);
        $Record->getEncja()->setLiderId($row['lider_id']);
        $Record->updateImmediately();
        $this->dataOut[$key] = array('success'=>'true');
      } catch (Exception $E) {
        $this->dataOut[$key] = array('success'=>'false');
        $this->catchLogicException($E);
      }
    }
  }

  public function fromRequest(&$_request) {
    foreach ($_request['data'] as $key => $value) {
      $row = array();
      if(isset($value['id'])){
        $row['id'] = $this->Firewall->int($value['lid']);
      }else{
        $row['id'] = null;
      }
      if(isset($value['lider_id'])){
        $row['lider_id'] = $this->Firewall->int($value['lider_id']);
      }else{
        $row['lider_id'] = null;
      }
      $this->dataIn[$key] = array();
    }
  }
  /**
   * @var ZespolyTable
   */
  protected $ZespolyTable;
}
