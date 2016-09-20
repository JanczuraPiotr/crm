<?php
/**
 * @package crmsw
 * @subpackage logic
 * @author Piotr Janczura <piotr@janczura.pl>
 * @task 2014-10-20 Przebudowa Table do obsługi zapytań preparowanych
 * @task 2014-10-20 Duża przebudowa DependencyTableRecord
 * @todo \pjpl\a\BusinessLogic <<< \crmsw\lib\a\BusinessLogic z usunięciem dziesdziczenia po klasie ...\beta\BusinessLogic
 */
class L_LiderzyRead extends \crmsw\lib\a\BusinessLogic{
	public function __construct() {
		parent::__construct();
		$this->tLiderzy = $this->DB->tableLiderzy();
		$this->tPracownicy = $this->DB->tablePracownicy();
		$this->tStanowiska = $this->DB->tableStanowiska();
	}
	protected function logic() {
		try{
			$this->tLiderzy->limit($this->dataIn['start'], $this->dataIn['limit'])->load();
			for($rLider = $this->tLiderzy->getRecordFirst(); $rLider !== NULL; $rLider = $this->tLiderzy->getRecordNext()){
				$row = array();
				$rStanowisko = $this->tStanowiska->getRecord($rLider->getEncja()->getStanowiskoId());
				$rPracownik = $this->tPracownicy->getRecord($rStanowisko->getEncja()->getPracownikId());
				$row['id'] = $rLider->getId();
				$row['symbol'] = $rLider->getEncja()->getSymbol();
				$row['nazwa'] = $rLider->getEncja()->getNazwa();
				$row['opis'] = $rLider->getEncja()->getOpis();
				$row['stanowisko_id'] = $rStanowisko->getId();
				$row['placowka_id'] = $rStanowisko->getEncja()->getPlacowkaId();
				$row['tel'] = $rStanowisko->getEncja()->getTel();
				$row['email'] = $rStanowisko->getEncja()->getEmail();
				$row['pracownik_id'] = $rPracownik->getId();
				$row['pracownik'] = $rPracownik->getEncja()->getNazwisko().' '.$rPracownik->getEncja()->getImie();
				$row['pesel'] = $rPracownik->getEncja()->getPesel();
				$row['data_od'] = $rLider->getEncja()->getDataOd();
				$row['data_do'] = $rLider->getEncja()->getDataDo();
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
	 * @var LiderzyTable
	 */
	protected $tLiderzy;
	/**
	 * @var StanowiskaTable
	 */
	protected $tStanowiska;
	/**
	 * @var PracownicyTable
	 */
	protected $tPracownicy;
}