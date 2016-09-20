<?php
/**
 * @package crmsw
 * @subpackage logic
 * @author Piotr Janczura <piotr@janczura.pl>
 * @task 2014-10-20 Przebudowa Table do obsługi zapytań preparowanych
 * @task 2014-10-20 Duża przebudowa DependencyTableRecord
 * @todo \pjpl\a\BusinessLogic <<< \crmsw\lib\a\BusinessLogic z usunięciem dziesdziczenia po klasie ...\beta\BusinessLogic
 */
class L_ZespolyRead extends \crmsw\lib\a\BusinessLogic{
	public function __construct() {
		parent::__construct();
		$this->tZespoly = $this->DB->tableZespoly();
		$this->tPracownicy = $this->DB->tablePracownicy();
		$this->tStanowiska = $this->DB->tableStanowiska();
	}
	protected function logic() {
		try{
			$this->tZespoly->loadStartLimit($this->dataIn['start'], $this->dataIn['limit']);
			for($rZespol = $this->tZespoly->getRecordFirst(); $rZespol !== NULL; $rZespol = $this->tZespoly->getRecordNext()){
				$row = array();
				$rStanowisko = $this->tStanowiska->getRecord($rZespol->getEncja()->getStanowiskoId());
				$rPracownik = $this->tPracownicy->getRecord($rStanowisko->getEncja()->getPracownikId());
				$row['id'] = $rZespol->getId();
				$row['lider_id'] = $rZespol->getEncja()->getLiderId();
				$row['stanowisko_id'] = $rZespol->getEncja()->getStanowiskoId();
				$row['stanowisko_symbol'] = $rStanowisko->getEncja()->getSymbol();
				$row['stanowisko_nazwa'] = $rStanowisko->getEncja()->getNazwa();
				$row['stanowisko_tel'] = $rStanowisko->getEncja()->getTel();
				$row['stanowisko_email'] = $rStanowisko->getEncja()->getEmail();
				$row['placowka_id'] = $rStanowisko->getEncja()->getPlacowkaId();
				$row['pracownik_id'] = $rPracownik->getId();
				$row['pracownik_nazwa'] = $rPracownik->getEncja()->getNazwisko().' '.$rPracownik->getEncja()->getImie();
				$row['pracownik_pesel'] = $rPracownik->getEncja()->getPesel();
				$row['data_od'] = $rZespol->getEncja()->getDataOd();
				$row['data_do'] = $rZespol->getEncja()->getDataDo();
				$this->dataOut[] = $row;
			}
		} catch (Exception $E) {
			$this->success = FALSE;
			$this->catchLogicException($E);
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
	 * @var ZespolyTable
	 */
	protected $tZespoly;
	/**
	 * @var StanowiskaTable
	 */
	protected $tStanowiska;
	/**
	 * @var PracownicyTable
	 */
	protected $tPracownicy;
}