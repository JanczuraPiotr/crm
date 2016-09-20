<?php
/**
 * @package crmsw
 * @subpackage database
 * @author Piotr Janczura <piotr@janczura.pl>
 * @done 2014-12-31
 * @todo \pjpl\a\BusinessLogic <<< \crmsw\lib\a\BusinessLogic z usunięciem dziesdziczenia po klasie ...\beta\BusinessLogic
 */
class L_PochodzenieKlientaCreate extends \crmsw\lib\a\BusinessLogic{
	public function __construct() {
		parent::__construct();
		$this->PochodzenieKlientowTable = $this->DB->tablePochodzenieKlientow();
	}
	protected function logic() {
		foreach ($this->dataIn as $key => $row) {
			try {
				$ePochodzenieKlienta = \PochodzenieKlienta::create($row);
				$rPochodzenieKlienta = $this->PochodzenieKlientowTable->createRecordImmediately($ePochodzenieKlienta);
				$this->dataOut[$key] = array('id'=>  $rPochodzenieKlienta->getId(),'tmpId'=>$row['tmpId'], 'rec' => $rPochodzenieKlienta->toArray());
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
	 * @var \PochodzenieKlientowTable
	 */
	protected $PochodzenieKlientowTable;
}