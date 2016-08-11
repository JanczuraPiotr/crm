<?php
/**
 * @package crmsw
 * @subpackage logic
 * @author Piotr Janczura <piotr@janczura.pl>
 * @work 2014-10-20 Przebudowa Table do obsługi zapytań preparowanych
 * @work 2014-10-20 Duża przebudowa DependencyTableRecord
 * @todo \pjpl\a\BusinessLogic <<< \crmsw\lib\a\BusinessLogic z usunięciem dziesdziczenia po klasie ...\beta\BusinessLogic
 */
class L_ZatrudnieniaReadById extends \crmsw\lib\a\BusinessLogic{
  public function __construct() {
    parent::__construct();
    $this->ZatrudnieniaTable = $this->DB->tableZatrudnienia();
  }
  protected function logic() {
    foreach ($this->dataIn as $key => $row) {
      try{
        $this->dataOut[$key] = array('success'=>'true','Zatrudnienie'=>$this->ZatrudnieniaTable->getRecord($row['id']));
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
        $row[$key] = $this->Firewall->int($value['id']);
        $this->dataIn[$key] = $row;
      }
    }
  }
  /**
   * @var ZatrudnieniaTable
   */
  protected $ZatrudnieniaTable;
}