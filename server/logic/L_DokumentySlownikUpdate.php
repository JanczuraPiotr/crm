<?php
/**
 * @package crmsw
 * @subpackage database
 * @author Piotr Janczura <piotr@janczura.pl>
 * @work 2014-10-20 Przebudowa Table do obsługi zapytań preparowanych
 * @work 2014-10-20 Duża przebudowa DependencyTableRecord
 * @todo \pjpl\a\BusinessLogic <<< \crmsw\lib\a\BusinessLogic z usunięciem dziesdziczenia po klasie ...\beta\BusinessLogic
 */
class L_DokumentySlownikUpdate extends \crmsw\lib\a\BusinessLogic{
	public function __construct() {
		parent::__construct();
		$this->DokumentySlownikTable = $this->DB->tableDokumentySlownik();
	}

	protected function logic() {
		foreach ($this->dataIn as $key => $row) {
			try{
				$Record = $this->DokumentySlownikTable->getRecord($row['id']);
				$DokumentSlownik = $Record->getEncja();
				$DokumentSlownik->setSymbol($row['symbol']);
				$DokumentSlownik->setNazwa($row['nazwa']);
				$Record->updateImmediately();
				$this->dataOut[$key] = array('success'=>true,'id'=>$row['id']);
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
			if(isset($value['id'])){
				$row['id'] = $this->Firewall->int($value['id']);
			}  else {
				$row['id'] = null;
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
	 * @var DokumentySlownikTable
	 */
	protected $DokumentySlownikTable;

}