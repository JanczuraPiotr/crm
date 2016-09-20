<?php
/**
 * @package crmsw
 * @subpackage logic
 * @author Piotr Janczura <piotr@janczura.pl>
 * @task 2014-10-20 Przebudowa Table do obsługi zapytań preparowanych
 * @task 2014-10-20 Duża przebudowa DependencyTableRecord
 * @todo \pjpl\a\BusinessLogic <<< \crmsw\lib\a\BusinessLogic z usunięciem dziesdziczenia po klasie ...\beta\BusinessLogic
 */
class L_LiderzyCreate extends \crmsw\lib\a\BusinessLogic{
	public function __construct() {
		parent::__construct();
		$this->tLiderzy = $this->DB->tableLiderzy();
	}

	protected function logic() {
		foreach ($this->dataIn as $key => $value) {
			try{
				$this->tLiderzy->__DEPRECIATE__setFiltrKeyValueAndAndRead(array('stanowisko_id'=>  $value['stanowisko_id'], 'data_do'=>NULL));// @depreciate
				if($this->tLiderzy->count() > 0){
					$this->dataOut[$key] = array('success'=>false, 'code'=>ERR_EDB_NOTUNIQUE, 'id'=>null,'tmpId'=>  $value['tmpId']);
					throw $E = new \pjpl\depreciate\EDBNotUnique(__CLASS__, __FILE__, $this->tLiderzy->getDI()->tableName(), '', '');
				}else{
					$this->create($key, $value);
				}
			}  catch (Exception $E){
				$this->success = FALSE;
				$this->catchLogicException($E);
			}
    }
	}
	private function create($key,$row){
		$Lider = new Lider(
						$row['stanowisko_id'],
						$row['symbol'],
						$row['nazwa'],
						$row['opis'],
						$row['data_od'],
						null
						);
		$this->dataOut[$key] = array('id'=>$this->tLiderzy->createRecordImmediately($Lider)->getId(),'tmpId'=>  $row['tmpId']);
	}
	public function fromRequest(&$_request) {
		$we = json_decode($_request, TRUE);
		foreach ($we['data'] as $key => $value) {
			$row =array();
			if(isset($value['tmpId'])){
				$row['tmpId'] = $this->Firewall->int($value['tmpId']);
			}  else {
				$row['tmpId'] = null;
			}
			if(isset($value['stanowisko_id'])){
				$row['stanowisko_id'] = $this->Firewall->int($value['stanowisko_id']);
			}  else {
				$row['stanowisko_id'] = NULL;
			}
			if(isset($value['symbol'])){
				$row['symbol'] = $this->Firewall->string($value['symbol']);
			}  else {
				$row['symbol'] = null;
			}
			if(isset($value['nazwa'])){
				$row['nazwa'] = $this->Firewall->string($value['nazwa']);
			}  else {
				$row['nazwa'] = null;
			}
			if(isset($value['opis'])){
				$row['opis'] = $this->Firewall->string($value['opis']);
			}  else {
				$row['opis'] = null;
			}
			if(isset($value['data_od'])){
				$row['data_od'] = $this->Firewall->date($value['data_od']);
			}  else {
				$row['data_od'] = date('Y-m-d');
			}
			$this->dataIn[$key] = $row;
		}
	}
	/**
	 * @var LiderzyTable
	 */
	protected $tLiderzy;
}