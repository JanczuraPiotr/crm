<?php
use crmsw\lib\a\BusinessLogic as BusinessLogic;
use pjpl\db\Where as Where;
/**
 * Lista możliwych statusów zadań.
 *
 * @package crmsw
 * @subpackage database
 * @author Piotr Janczura <piotr@janczura.pl>
 * @confirm 2014-11-04 Przebudowa Table do obsługi zapytań preparowanych
 * @confirm 2014-11-04 Duża przebudowa DependencyTableRecord
 * @todo \pjpl\a\BusinessLogic <<< \crmsw\lib\a\BusinessLogic z usunięciem dziesdziczenia po klasie ...\beta\BusinessLogic
 * @todo Klasa ma złą nazwę - nie wskazuje na status konkretnego zadania lecz na znane typy statusów.
 */
class L_StatusZadaniaRead extends BusinessLogic{
	public function __construct() {
		parent::__construct();
		$this->tStatusyZadan = $this->DB->tableStatusyZadan();
	}
	protected function logic() {
		try{
			$Where = new Where($this->dataIn['filter']);
			$this->tStatusyZadan->where($Where)->limit($this->dataIn['start'], $this->dataIn['limit'])->load();
			for($rStatusZadania = $this->tStatusyZadan->getRecordFirst() ; $rStatusZadania !== NULL ; $rStatusZadania = $this->tStatusyZadan->getRecordNext()){
				$this->dataOut[] = $rStatusZadania->toArray();
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
		}else{
			$this->dataIn['filter'] = [];
		}
	}
	/**
	 * @var StatusyZadanTable
	 */
	protected $tStatusyZadan;

}