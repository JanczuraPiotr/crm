<?php
/**
 * @package crmsw
 * @subpackage database
 * @author Piotr Janczura <piotr@janczura.pl>
 * @work 2014-10-20 Przebudowa Table do obsługi zapytań preparowanych
 * @work 2014-10-20 Duża przebudowa DependencyTableRecord
 * @todo \pjpl\a\BusinessLogic <<< \crmsw\lib\a\BusinessLogic z usunięciem dziesdziczenia po klasie ...\beta\BusinessLogic
 */
class L_StanowiskaZwykleRead extends \crmsw\lib\a\BusinessLogic{
	public function __construct() {
		parent::__construct();
		$this->tStanowiska = $this->DB->tableStanowiska();
		$this->tPracownicy = $this->DB->tablePracownicy();
	}
	protected function logic() {
		try{
			$filtr = array($this->dataIn['filter'][0]['property'] => $this->dataIn['filter'][0]['value'],$this->dataIn['filter'][1]['property'] => $this->dataIn['filter'][1]['value']);
			$this->tStanowiska->/*@depreciate*/ __DEPRECIATE__setFiltrKeyValueAndAndRead($filtr);
			for($rStanowisko = $this->tStanowiska->getRecordFirst(); $rStanowisko !== NULL; $rStanowisko = $this->tStanowiska->getRecordNext()){
				$pracownik_id = $rStanowisko->getEncja()->getPracownikId();
				$pracownik = array();
				if($pracownik_id !== null){
					$Pracownik = $this->tPracownicy->getRecord($pracownik_id);
					$pracownik['pracownik'] = $Pracownik->getEncja()->getNazwisko().' '.$Pracownik->getEncja()->getImie();
					$pracownik['pesel'] = $Pracownik->getEncja()->getPesel();
				}else{
					$pracownik['pracownik'] = 'Wakat';
					$pracownik['pesel'] = '';
				}
				$this->dataOut[] = array_merge($this->tStanowiska->getDI()->/*@depreciate*/ __DEPRECIATE__fromRecordToArray($rStanowisko) , $pracownik);
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
			$this->dataIn['filter'] = json_decode($_request['filter'],TRUE);
		}  else {
			$this->dataIn['filter'] = null;
		}
	}
	public function getJson(){
    return json_encode(array('success'=>  $this->success, 'totalCount'=> $this->tStanowiska->countTotal(), 'count'=>  $this->tStanowiska->count(), 'data'=>  $this->dataOut));
	}

	/**
	 * @var StanowiskaTable
	 */
	protected $tStanowiska;
	/**
	 * @var PracownicyTable
	 */
	protected $tPracownicy;
	/**
	 * @var StatusyStanowiskTable
	 */
	protected $tStatusyStanowisk;
}