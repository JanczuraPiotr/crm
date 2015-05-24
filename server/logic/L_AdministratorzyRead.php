<?php
/**
 * @package crmsw
 * @subpackage logic
 * @author Piotr Janczura <piotr@janczura.pl>
 * @confirm 2014-12-16
 * @todo \pjpl\a\BusinessLogic <<< \crmsw\lib\a\BusinessLogic z usunięciem dziesdziczenia po klasie ...\beta\BusinessLogic
 */
class L_AdministratorzyRead extends \crmsw\lib\a\BusinessLogic{
 public function __construct() {
    parent::__construct();
    $this->AdministratorzyTable = $this->DB->tableAdministratorzy();
  }
	protected function logic() {
		try{
			$this->AdministratorzyTable->limit($this->dataIn['start'], $this->dataIn['limit'])->load();
			$Record = $this->AdministratorzyTable->getRecordFirst();
			do{
				$this->dataOut[] = $Record->toArray();
			}while($Record = $this->AdministratorzyTable->getRecordNext());
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
	}
	public function getJson(){
    return json_encode(array('success'=>  $this->success, 'totalCount'=> $this->AdministratorzyTable->countTotal(), 'count'=>  $this->AdministratorzyTable->count(), 'data'=>  $this->dataOut));
	}

	/**
   * @var AdministratorzyTable
   */
  protected $AdministratorzyTable;
}