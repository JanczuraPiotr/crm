<?php
use pjpl\db\Where;
/**
 * @package crmsw
 * @subpackage database
 * @author Piotr Janczura <piotr@janczura.pl>
 * @done 2014-12-31
 * @todo \pjpl\a\BusinessLogic <<< \crmsw\lib\a\BusinessLogic z usunięciem dziesdziczenia po klasie ...\beta\BusinessLogic
 */
class L_PochodzenieKlientaRead extends \crmsw\lib\a\BusinessLogic{
	public function __construct() {
		parent::__construct();
		$this->PochodzenieKlientaTable = $this->DB->tablePochodzenieKlientow();
	}


	protected function logic() {
		try{
			$this->PochodzenieKlientaTable->limit($this->dataIn['start'], $this->dataIn['limit'])->load();
			$Record = $this->PochodzenieKlientaTable->getRecordFirst();
			do{
				$this->dataOut[] = $Record->toArray();
			}while($Record = $this->PochodzenieKlientaTable->getRecordNext());
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
//	public function getJson(){
//    return json_encode(array('success'=>  $this->success, 'totalCount'=> $this->PochodzenieKlientaTable->countTotal(), 'count'=>  $this->PochodzenieKlientaTable->count(), 'data'=>  $this->dataOut));
//	}
	/**
	 * @var \PochodzenieKlientaTable
	 */
	protected $PochodzenieKlientaTable;

}