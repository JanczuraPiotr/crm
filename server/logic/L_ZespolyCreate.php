<?php
/**
 * @package crmsw
 * @subpackage logic
 * @author Piotr Janczura <piotr@janczura.pl>
 * @prace 2014-10-20 Przebudowa Table do obsługi zapytań preparowanych
 * @prace 2014-10-20 Duża przebudowa DependencyTableRecord
 * @todo \pjpl\a\BusinessLogic <<< \crmsw\lib\a\BusinessLogic z usunięciem dziesdziczenia po klasie ...\beta\BusinessLogic
 */

class L_ZespolyCreate extends \crmsw\lib\a\BusinessLogic{
  public function __construct() {
    parent::__construct();
    $this->ZespolyTable = $this->DB->tableZespoly();
  }
  protected function logic() {
    foreach ($this->dataIn as $key => $value) {
      try{
				$this->ZespolyTable->__DEPRECIATE__setFiltrKeyValueAndAndRead(array('stanowisko_id'=>  $value['stanowisko_id'], 'data_do'=>NULL));// @depreciate
				if($this->ZespolyTable->count() > 0){
					$this->dataOut[$key] = array('success'=>false, 'code'=>ERR_EDB_NOTUNIQUE, 'id'=>null,'tmpId'=>  $value['tmpId']);
					throw $E = new \pjpl\depreciate\EDBNotUnique(__CLASS__, __FILE__, $this->ZespolyTable->getDI()->tableName(), '', '');
				}
        $Zespol = new Zespol(
								$value['lider_id'],
								$value['stanowisko_id'],
								$value['data_od'],
								NULL
								);
				$this->dataOut[$key] = array('id'=>$this->ZespolyTable->createRecordImmediately($Zespol)->getId(),'tmpId'=>  $value['tmpId']);
      } catch (Exception $E) {
        $this->success = FALSE;
        $this->catchLogicException($E);
      }
    }
  }

  public function fromRequest(&$_request) {
		$we = json_decode($_request, TRUE);
		foreach ($we['data'] as $key => $value) {
			$row =array();
			if(isset($value['tmpId'])){
				$row['tmpId'] = $this->Firewall->login($value['tmpId']);
			}  else {
				$row['tmpId'] = null;
			}
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
  /**
   * @var ZespolyTable
   */
  protected $ZespolyTable;
}
