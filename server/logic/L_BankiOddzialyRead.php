<?php
/**
 * @package crmsw
 * @subpackage logic
 * @author Piotr Janczura <piotr@janczura.pl>
 * @prace 2014-10-20 Przebudowa Table do obsługi zapytań preparowanych
 * @prace 2014-10-20 Duża przebudowa DependencyTableRecord
 * @todo \pjpl\a\BusinessLogic <<< \crmsw\lib\a\BusinessLogic z usunięciem dziesdziczenia po klasie ...\beta\BusinessLogic
 */

class L_BankiOddzialyRead extends \crmsw\lib\a\BusinessLogic{
  public function __construct() {
    parent::__construct();
    $this->BankiOddzialyTable = $this->DB->tableBankiOddzialy();
  }
  protected function logic() {
    foreach ($this->dataIn as $key => $row) {
			try{
				$this->BankiOddzialyTable->limit($this->dataIn['start'], $this->dataIn['limit'])->load();
				$Record = $this->BankiOddzialyTable->getRecordFirst();
				do{
					$this->dataOut[] = $this->BankiOddzialyTable->getDI()->/*@depreciate*/ __DEPRECIATE__fromRecordToArray($Record);
				}while($Record = $this->BankiOddzialyTable->getRecordNext());
			} catch (Exception $ex) {
				$this->success = FALSE;
				$this->catchLogicException($ex);
			}
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
			$this->dataIn['filter'] = json_decode($_request['filter'],TRUE);
		}  else {
			$this->dataIn['filter'] = null;
		}
  }
	public function getJson(){
    return json_encode(array('success'=>  $this->success, 'totalCount'=> $this->BankiOddzialyTable->countTotal(), 'count'=>  $this->BankiOddzialyTable->count(), 'data'=>  $this->dataOut));
	}

  /**
   * @var BankiOddzialyTable
   */
  protected $BankiOddzialyTable;
}
