<?php
use crmsw\lib\a\BusinessLogic;
use pjpl\db\Where;
/**
 * @package crmsw
 * @subpackage database
 * @author Piotr Janczura <piotr@janczura.pl>
 * @done 2014-12-20
 * @todo \pjpl\a\BusinessLogic <<< \crmsw\lib\a\BusinessLogic z usunięciem dziesdziczenia po klasie ...\beta\BusinessLogic
 */

class L_ProduktyRead extends BusinessLogic{
	public function __construct() {
		parent::__construct();
		$this->ProduktyTable = $this->DB->tableProdukty();
	}
	protected function logic() {
		try{
			$Where = new Where([
							[
											'attribute' => 'data_do',
											'operator'  => '=',
											'value'     => NULL
							]
			]);
			$this->ProduktyTable->where($Where)->limit($this->dataIn['start'], $this->dataIn['limit'])->load();
			$Record = $this->ProduktyTable->getRecordFirst();
			do{
				$this->dataOut[] = $Record->toArray();
			}while($Record = $this->ProduktyTable->getRecordNext());
			$this->countTodalOut = $this->ProduktyTable->countTotal();
			$this->countFilteredOut = $this->ProduktyTable->countFiltered();
			$this->countOut = $this->ProduktyTable->count();
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
//	public function getJson(){
//    return json_encode(array('success'=>  $this->success, 'totalCount'=> $this->ProduktyTable->countTotal(), 'count'=>  $this->ProduktyTable->count(), 'data'=>  $this->dataOut));
//	}
	/**
	 * @var ProduktyTable
	 */
	protected $ProduktyTable;
}