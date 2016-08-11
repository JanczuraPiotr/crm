<?php
use crmsw\lib\a\BusinessLogic;
/**
 * @package crmsw
 * @subpackage database
 * @author Piotr Janczura <piotr@janczura.pl>
 * @work 2014-10-20 Przebudowa Table do obsługi zapytań preparowanych
 * @work 2014-10-20 Duża przebudowa DependencyTableRecord
 * @todo \pjpl\a\BusinessLogic <<< \crmsw\lib\a\BusinessLogic z usunięciem dziesdziczenia po klasie ...\beta\BusinessLogic
 */
class L_StanowiskaLiderowKooperantowRead extends BusinessLogic{
	public function __construct() {
		parent::__construct();
		$this->tStanowiska = $this->DB->tableStanowiska();
		$this->tPracownicy = $this->DB->tablePracownicy();
	}
	protected function logic() {
		try{
			$this->tStanowiska-> __DEPRECIATE__setFiltrKeyValueAndAndRead(array('placowka_id'=>(int)$_SESSION['PLACOWKA_ID'],'status_stanowiska_id'=>4));// @depreciate
			for($rStanowisko = $this->tStanowiska->getRecordFirst(); $rStanowisko !== NULL; $rStanowisko = $this->tStanowiska->getRecordNext()){
				if((int)$rStanowisko->getId() === (int)$_SESSION['STANOWISKO_ID']){
					continue;
				}
				$pracownik_id = $rStanowisko->getEncja()->getPracownikId();
				$pracownik = array();
				$Pracownik = $this->tPracownicy->getRecord($pracownik_id);
				$pracownik['pracownik'] = $Pracownik->getEncja()->getNazwisko().' '.$Pracownik->getEncja()->getImie();
				$pracownik['pesel'] = $Pracownik->getEncja()->getPesel();
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