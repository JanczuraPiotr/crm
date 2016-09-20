<?php
/**
 * @package crmsw
 * @subpackage logic
 * @author Piotr Janczura <piotr@janczura.pl>
 * @task 2014-10-20 Przebudowa Table do obsługi zapytań preparowanych
 * @task 2014-10-20 Duża przebudowa DependencyTableRecord
 * @todo \pjpl\a\BusinessLogic <<< \crmsw\lib\a\BusinessLogic z usunięciem dziesdziczenia po klasie ...\beta\BusinessLogic
 */

class L_ZespolyUpdate extends \crmsw\lib\a\BusinessLogic{
  public function __construct() {
    parent::__construct();
    $this->tZespoly = $this->DB->tableZespoly();
  }
  protected function logic() {
    foreach ($this->dataIn as $key => $row) {
      try{
				$rZespol = $this->tZespoly->getRecord($row['id']);
				$eZespol = $rZespol->getEncja();
				$eZespol->setLiderId($row['lider_id']);
				$eZespol->setStanowiskoId($row['stanowisko_id']);
				$eZespol->setDataOd($row['data_od']);
				$eZespol->setDataDo($row['data_do']);
				$rZespol->updateImmediately();
				$this->dataOut[$key] = array('success'=>true,'id'=>$row['id']);
      } catch (Exception $E) {
        $this->dataOut[$key] = array('success'=>'false');
        $this->catchLogicException($E);
      }
    }
  }

  public function fromRequest(&$_request) {
		$we = json_decode($_request, TRUE);
		foreach ($we['data'] as $key => $value) {
			$row =array();
			if(isset($value['id'])){
				$row['id'] = $this->Firewall->int($value['id']);
			}else{
				$row['id'] = null;
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
			if(isset($value['data_do'])){
				$row['data_do'] = $this->Firewall->date($value['data_do']);
			}else{
				$row['data_do'] = null;//$row['data_do'] = date('Y=m-d');
			}
			$this->dataIn[$key] = $row;
    }
  }
  /**
   * @var ZespolyTable
   */
  protected $tZespoly;
}
