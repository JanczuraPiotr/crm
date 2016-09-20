<?php
/**
 * @package crmsw
 * @subpackage logic
 * @author Piotr Janczura <piotr@janczura.pl>
 * @task 2014-10-20 Przebudowa Table do obsługi zapytań preparowanych
 * @task 2014-10-20 Duża przebudowa DependencyTableRecord
 * @todo \pjpl\a\BusinessLogic <<< \crmsw\lib\a\BusinessLogic z usunięciem dziesdziczenia po klasie ...\beta\BusinessLogic
 */

class L_ZespolyDodajStanowisko extends \crmsw\lib\a\BusinessLogic{
  public function __construct() {
    parent::__construct();
    $this->ZespolyTable = $this->DB->tableZespoly();
  }
  protected function logic() {
    foreach ($this->dataOut as $key => $row) {
      try{
        $Zespul = new Zespol($row['lider_id'], $row['stanowisko_id'], $row['data_od'], NULL);
        $this->dataOut[$key] = array('success'=>'true','id'=>  $this->ZespolyTable->createRecordImmediately($Zespul));
      } catch (Exception $E) {
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
        }else{
          $row['lider_id'] = null;
        }
        if(isset($value['stanowisko_id'])){
          $row['stanowisko_id'] = $this->Firewall->int($value['stanowisko_id']);
        }else{
          $row['stanowisko_id'] = null;
        }
        if(isset($value['data_od'])){
          $row['data_od'] = $this->Firewall->date($value['data_od']);
        }else{
          $row['data_od'] = null;//$row['data_od'] = date('Y=m-d');
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
