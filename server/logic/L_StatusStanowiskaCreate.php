<?php
/**
 * @package crmsw
 * @subpackage database
 * @author Piotr Janczura <piotr@janczura.pl>
 * @work 2014-10-20 Przebudowa Table do obsługi zapytań preparowanych
 * @work 2014-10-20 Duża przebudowa DependencyTableRecord
 * @todo \pjpl\a\BusinessLogic <<< \crmsw\lib\a\BusinessLogic z usunięciem dziesdziczenia po klasie ...\beta\BusinessLogic
 */
class L_StatusStanowiskaCreate extends \crmsw\lib\a\BusinessLogic{
	public function __construct() {
		parent::__construct();
		$this->StatusyStanowiskTable = $this->DB->tableStatusyStanowisk();
	}
	protected function logic() {
		foreach ($this->dataIn as $key => $row) {
			try {
				$StatusStanowiska = new StatusStanowiska($row['symbol'],$row['opis']);
				$this->dataOut[$key] = array('id'=>  $this->StatusyStanowiskTable->createRecordImmediately($StatusStanowiska)->getId(),'tmpId'=>$row['tmpId']);
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
			if(isset($value['opis'])){
				$row['opis'] = $this->Firewall->string($value['opis']);
			}else{
				$row['opis'] = null;
			}
			$this->dataIn[$key] = $row;
		}
	}
	/**
	 * @var StatusyStanowiskTable
	 */
	protected $StatusyStanowiskTable;
}