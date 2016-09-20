<?php
use crmsw\lib\a\BusinessLogic
		as BusinessLogic;
use pjpl\db\Where
		as Where;
use FirmyOddzialyTable
		as FirmyOddzialyTable;
/**
 * @package crmsw
 * @subpackage logic
 * @author Piotr Janczura <piotr@janczura.pl>
 * @done 2014-12-29
 * @todo \pjpl\a\BusinessLogic <<< \crmsw\lib\a\BusinessLogic z usunięciem dziesdziczenia po klasie ...\beta\BusinessLogic
 */

class L_FirmyOddzialyRead extends BusinessLogic{
	public function __construct() {
		parent::__construct();
		$this->FirmyOddzialyTable = $this->DB->tableFirmyOddzialy();
	}
	protected function logic() {
		try{
			$Where = new Where($this->dataIn['filter']);
			$this->FirmyOddzialyTable->where($Where)->limit($this->dataIn['start'], $this->dataIn['limit'])->load();
			$Record = $this->FirmyOddzialyTable->getRecordFirst();
			do{
				$this->dataOut[] = $Record->toArray();
			}while($Record = $this->FirmyOddzialyTable->getRecordNext());
		} catch (\Exception $ex) {
			$this->success = FALSE;
			$this->catchLogicException($ex);
		}
	}
	public function fromRequest(&$_request) {
		if(isset($_request['page'])){
			$this->dataIn['page'] = $this->Firewall->int($_request['page']);
		}  else {
			$this->dataIn['page'] = 0;
		}
		if(isset($_request['start'])){
			$this->dataIn['start'] = $this->Firewall->int($_request['start']);
		}  else {
			$this->dataIn['start'] = 0;
		}
		if(isset($_request['limit'])){
			$this->dataIn['limit'] = $this->Firewall->int($_request['limit']);
		}else{
			$this->dataIn['limit'] = 0;
		}
		if(isset($_request['filter'])){
			$this->dataIn['filter'] = $this->reformatExtJSFilter($_request['filter']);
		}else{
			$this->dataIn['filter'] = [];
		}
	}

	/**
	 * @var \FirmyOddzialyTable
	 */
	protected $FirmyOddzialyTable;
}
