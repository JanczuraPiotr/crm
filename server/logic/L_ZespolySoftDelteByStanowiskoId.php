<?php
/**
 * @package crmsw
 * @subpackage logic
 * @author Piotr Janczura <piotr@janczura.pl>
 * @work 2014-10-20 Przebudowa Table do obsługi zapytań preparowanych
 * @work 2014-10-20 Duża przebudowa DependencyTableRecord
 * @todo Kasowanie rekordu zablokowanego kluczem podrzędnym powinna ustawić data_do na null
 * @todo \pjpl\a\BusinessLogic <<< \crmsw\lib\a\BusinessLogic z usunięciem dziesdziczenia po klasie ...\beta\BusinessLogic
 */

class L_ZespolySoftDelteByStanowiskoId extends \crmsw\lib\a\BusinessLogic{
  public function __construct() {
    parent::__construct();
    $this->ZespolyTable = $this->DB->tableZespoly();
  }
  protected function logic() {
    foreach ($this->dataIn as $key => $row) {
      try{
        $this->ZespolyTable->setFiltrKeyValueAndAndRead(array('stanowisko_id'=>$row['stanowisko_id']));
        $Zespol = $this->ZespolyTable->getRecordFirst();
        $Zespol->softDelete($row['data_do']);
        $this->dataOut[$key] = array('success'=>'true','id'=>$id);
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
        if(isset($value['stanowisko_id'])){
          $row['stanowisko_id'] = $this->Firewall->int($value['stanowisko_id']);
        }else{
          $row['stanowisko_id'] = null;
        }
        if(isset($value['data_do'])){
          $row['data_do'] = $this->Firewall->date($value['data_do']);
        }else{
          $row['data_do'] = null;//$row['data_do'] = date('Y=m-d');
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
