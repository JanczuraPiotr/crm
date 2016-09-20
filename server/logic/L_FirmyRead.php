<?php
/**
 * @package crmsw
 * @subpackage logic
 * @author Piotr Janczura <piotr@janczura.pl>
 * @done 2014-12-15
 * @todo Kasowanie rekordu zablokowanego kluczem podrzędnym powinna ustawić data_do na null
 * @todo \pjpl\a\BusinessLogic <<< \crmsw\lib\a\BusinessLogic z usunięciem dziesdziczenia po klasie ...\beta\BusinessLogic
 */

class L_FirmyRead extends \crmsw\lib\a\BusinessLogic{
  public function __construct() {
    parent::__construct();
    $this->FirmyTable = $this->DB->tableFirmy();
  }

  protected function logic() {
		try{
			$this->FirmyTable->limit($this->dataIn['start'], $this->dataIn['limit'])->load();
			$Record = $this->FirmyTable->getRecordFirst();
			do{
				$this->dataOut[] = $Record->toArray();
			}while($Record = $this->FirmyTable->getRecordNext());
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
	public function getJson(){
    return json_encode(array(
						'success'=>  $this->success,
						'totalCount'=> $this->FirmyTable->countTotal(),
						'count'=>  $this->FirmyTable->count(),
						'data'=>  $this->dataOut
						));
	}

  /**
   * @var \FirmyTable
   */
  protected $FirmyTable;
}
