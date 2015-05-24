<?php
/**
 * @package crmsw
 * @subpackage logic
 * @author Piotr Janczura <piotr@janczura.pl>
 * @confirm 2014-12-30
 * @todo \pjpl\a\BusinessLogic <<< \crmsw\lib\a\BusinessLogic z usunięciem dziesdziczenia po klasie ...\beta\BusinessLogic
 */
class L_PracownicyRead extends \crmsw\lib\a\BusinessLogic{
  public function __construct() {
    parent::__construct();
    $this->PracownicyTable = $this->DB->tablePracownicy();
  }

  protected function logic() {
		try{
			$this->PracownicyTable->limit($this->dataIn['start'], $this->dataIn['limit'])->load();
			$Record = $this->PracownicyTable->getRecordFirst();
			do{
				$this->dataOut[] = $Record->toArray();
			}while($Record = $this->PracownicyTable->getRecordNext());
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
//    return json_encode(array('success'=>  $this->success, 'totalCount'=> $this->PracownicyTable->countTotal(), 'count'=>  $this->PracownicyTable->count(), 'data'=>  $this->dataOut));
//	}
  /**
   * @var \PracownicyTable
   */
  protected $PracownicyTable;
}