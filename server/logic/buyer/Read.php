<?php
namespace crmsw\logic\buyer;
use crmsw\lib\a\BusinessLogic;
use pjpl\db\Where;
/**
 * @package crmsw
 * @subpackage logic
 * @author Piotr Janczura <piotr@janczura.pl>
 * @confirm 2014-12-15 Przebudowa Table do obsługi zapytań preparowanych
 * @todo \pjpl\a\BusinessLogic <<< \crmsw\lib\a\BusinessLogic z usunięciem dziesdziczenia po klasie ...\beta\BusinessLogic
 *
 */
class Read extends BusinessLogic{
  public function __construct() {
    parent::__construct();
    $this->KlienciTable = $this->DB->tableKlienci();
  }
	protected function logic() {
		try {
			$Where = new Where($this->dataIn['filter']);
			$this->KlienciTable->where($Where)->limit($this->dataIn['start'], $this->dataIn['limit'])->load();
			for($rKlient = $this->KlienciTable->getRecordFirst(); $rKlient !== NULL; $rKlient = $this->KlienciTable->getRecordNext() ){
				$this->dataOut[] = $rKlient->toArray();
			}
			$this->countTodalOut = $this->KlienciTable->countTotal();
			$this->countFilteredOut = $this->KlienciTable->countFiltered();
			$this->countOut = $this->KlienciTable->count();
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
		}  else {
			$this->dataIn['filter'] = [];
		}
	}
	/**
   * @var \KlienciTable
   */
  protected $KlienciTable;
}