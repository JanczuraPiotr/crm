<?php
use pjpl\db\Where as Where;
use crmsw\lib\a\BusinessLogic as BusinessLogic;
/**
 * @package crmsw
 * @subpackage logic
 * @author Piotr Janczura <piotr@janczura.pl>
 * @done 2014-11-05 Przebudowa Table do obsługi zapytań preparowanych
 * @done 2014-10-20 Duża przebudowa DependencyTableRecord
 * @todo \pjpl\a\BusinessLogic <<< \crmsw\lib\a\BusinessLogic z usunięciem dziesdziczenia po klasie ...\beta\BusinessLogic
 */
class L_OsobyPowiazaneRead extends BusinessLogic{
  public function __construct() {
    parent::__construct();
    $this->OsobyPowiazaneTable = $this->DB->tableOsobyPowiazane();
  }
	protected function logic() {
		try{
			$where = new Where($this->dataIn['filter']);
			$this->OsobyPowiazaneTable->where($where)->limit($this->dataIn['start'], $this->dataIn['limit'])->load();
			for( $Record = $this->OsobyPowiazaneTable->getRecordFirst(); $Record !== NULL; $Record = $this->OsobyPowiazaneTable->getRecordNext() ){
				$this->dataOut[] = $Record->toArray();
			}
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
   * @var OsobyPowiazaneTable
   */
  protected $OsobyPowiazaneTable;
}