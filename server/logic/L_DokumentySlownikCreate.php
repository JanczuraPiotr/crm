<?php
/**
 * @package crmsw
 * @subpackage database
 * @author Piotr Janczura <piotr@janczura.pl>
 * @confirm 2014-12-22
 * @todo \pjpl\a\BusinessLogic <<< \crmsw\lib\a\BusinessLogic z usunięciem dziesdziczenia po klasie ...\beta\BusinessLogic
 */
class L_DokumentySlownikCreate extends \crmsw\lib\a\BusinessLogic{
	public function __construct() {
		parent::__construct();
		$this->DokumentySlownikTable = $this->DB->tableDokumentySlownik();
	}
	protected function logic() {
		foreach ($this->dataIn as $key => $row) {
			try {
				$DokumentSlownik =  \DokumentSlownik::create([
						'symbol' => $row['symbol'],
						'nazwa'  => $row['nazwa']
				]);
				$this->dataOut[$key] = array('id'=>  $this->DokumentySlownikTable->createRecordImmediately($DokumentSlownik)->getId(),'tmpId'=>$row['tmpId']);
			} catch (Exception $E) {
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
			if(isset($value['nazwa'])){
				$row['nazwa'] = $this->Firewall->string($value['nazwa']);
			}else{
				$row['nazwa'] = null;
			}
			$this->dataIn[$key] = $row;
		}
	}
	/**
	 * @var \DokumentySlownikTable
	 */
	protected $DokumentySlownikTable;
}