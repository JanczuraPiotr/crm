<?php
use crmsw\lib\a\BusinessLogic;
use pjpl\db\Where;
/**
 * @package crmsw
 * @subpackage logic
 * @author Piotr Janczura <piotr@janczura.pl>
 * @confirm 2014-12-20
 * @todo \pjpl\a\BusinessLogic <<< \crmsw\lib\a\BusinessLogic z usunięciem dziesdziczenia po klasie ...\beta\BusinessLogic
 */
class L_BankiRead extends BusinessLogic{
  public function __construct() {
    parent::__construct();
    $this->BankiTable = $this->DB->tableBanki();
  }
  protected function logic() {
		try{
			$Where = new Where($this->dataIn['filter']);
			$this->BankiTable->where($Where)->limit($this->dataIn['start'], $this->dataIn['limit'])->load();
			$Record = $this->BankiTable->getRecordFirst();
			do{
				$this->dataOut[] = $Record->toArray();
			}while($Record = $this->BankiTable->getRecordNext());
			$this->countTodalOut = $this->BankiTable->countTotal();
			$this->countFilteredOut = $this->BankiTable->countFiltered();
			$this->countOut = $this->BankiTable->count();
		} catch (Exception $ex) {
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
   * @var BankiTable
   */
  protected $BankiTable;
}