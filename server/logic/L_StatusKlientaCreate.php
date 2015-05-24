<?php
/**
 * @package crmsw
 * @subpackage database
 * @author Piotr Janczura <piotr@janczura.pl>
 * @prace 2014-10-20 Przebudowa Table do obsługi zapytań preparowanych
 * @prace 2014-10-20 Duża przebudowa DependencyTableRecord
 * @todo \pjpl\a\BusinessLogic <<< \crmsw\lib\a\BusinessLogic z usunięciem dziesdziczenia po klasie ...\beta\BusinessLogic
 */
class L_StatusKlientaCreate extends \crmsw\lib\a\BusinessLogic{
	public function __construct() {
		parent::__construct();
		$this->StatusyKlientowTable = $this->DB->tableStatusyKlientow();
	}
	protected function logic() {
		foreach ($this->dataIn as $key => $row) {
			try {
				$StatusKlienta = new StatusKlienta($row['symbol'], $row['status'],$row['opis']);
				$this->dataOut[$key] = array('id'=>  $this->StatusyKlientowTable->createRecordImmediately($StatusKlienta)->getId(),'tmpId'=>$row['tmpId']);
			} catch (\Exception $E) {
				$this->success = FALSE;
				$this->catchLogicException($E);
			}
		}
	}
	public function fromRequest(&$_request) {
		$we = json_decode($_request,TRUE);
		foreach ($we['data'] as $key => $value) {
			$row = array();
			if(isset($value['tmpId'])){
				$row['tmpId'] = $this->Firewall->int($value['tmpId']);
			}  else {
				$row['tmpId'] = null;
			}
			if(isset($value['symbol'])){
				$row['symbol'] = $this->Firewall->word($value['symbol']);
			}else{
				$row['symbol'] = null;
			}
			if(isset($value['status'])){
				$row['status'] = $this->Firewall->word($value['status']);
			}else{
				$row['status'] = null;
			}
			if(isset($value['opis'])){
				$row['opis'] = $this->Firewall->string($value['opis']);
			}else{
				$row['opis'] = null;
			}
			$this->dataIn[$key] = $row;
		}
	}
	/**
	 * @var StatusyKlientowTable
	 */
	protected $StatusyKlientowTable;
}