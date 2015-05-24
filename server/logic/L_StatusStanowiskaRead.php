<?php
/**
 * @package crmsw
 * @subpackage database
 * @author Piotr Janczura <piotr@janczura.pl>
 * @confirm 2014-12-15
 * @confirm 2014-12-15 Przebudowa Table do obsługi zapytań preparowanych
 * @confirm 2014-12-15 Duża przebudowa DependencyTableRecord
 * @todo \pjpl\a\BusinessLogic <<< \crmsw\lib\a\BusinessLogic z usunięciem dziesdziczenia po klasie ...\beta\BusinessLogic
 */
class L_StatusStanowiskaRead extends \crmsw\lib\a\BusinessLogic{
	public function __construct() {
		parent::__construct();
		$this->StatusyStanowiskTable = $this->DB->tableStatusyStanowisk();
	}
	protected function logic() {
		try{
			$this->StatusyStanowiskTable->limit($this->dataIn['start'], $this->dataIn['limit'])->load();
			$Record = $this->StatusyStanowiskTable->getRecordFirst();
			do{
				if($Record->getId() < 4){
					/**
					 * Chilowo nie zawracam sibie głowy tworzeniem stanowisk pracy dla zarządu
					 * @todo Opracować sposób przydzielania stanowisk pracy zarządowi.
					 */
					continue;
				}
				$this->dataOut[] =  $Record->toArray();
			}while($Record = $this->StatusyStanowiskTable->getRecordNext());
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
		$this->dataIn['filter'] = $this->reformatExtJSFilter($_request['filter']);
	}
	public function getJson(){
    return json_encode(array('success'=>  $this->success, 'totalCount'=> $this->StatusyStanowiskTable->countTotal(), 'count'=>  $this->StatusyStanowiskTable->count(), 'data'=>  $this->dataOut));
	}

	/**
	 * @var StatusyStanowiskTable
	 */
	protected $StatusyStanowiskTable;

}