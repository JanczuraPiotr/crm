<?php
namespace crmsw\logic\teams;
/**
 * @package crmsw
 * @subpackage database
 * @author Piotr Janczura <piotr@janczura.pl>
 * @confirm 2014-08-28
 */

/**
 * Wyszukuje stanowiska należące do jednego zespołu.
 * Jeżeli w zapytaniu podano wartość zmiennej lider_id to wyszuka stanowiska podległe temu liderowi.
 * Jeżeli w wywołaniu nie podano zmiennej lider_id uznawane jest że chodzi o zespół pracownika z konta którego wykonywany jest test.
 */
class ReadTeamsWorkplace extends \crmsw\lib\a\BusinessLogic{
	public function __construct() {
		parent::__construct();
		$this->tStanowiska = $this->DB->tableStanowiska();
		$this->tPracownicy = $this->DB->tablePracownicy();
		$this->tLiderzy = $this->DB->tableLiderzy();
		$this->tZespoly = $this->DB->tableZespoly();
	}
	protected function logic() {
		try{
			if(isset($this->dataIn['filter'][0]['property']) && $this->dataIn['filter'][0]['property'] === 'lider_id'){
				$lider_id = $this->dataIn['filter'][0]['value'];
				$this->tZespoly->setFiltrKeyValueAndAndRead(array('lider_id'=>$lider_id));
			}else{
				$lider_id = NULL;
				if((int)$_SESSION['USER_STATUS'] === \CRM::PRACOWNIK_LIDER || (int)$_SESSION['USER_STATUS'] === \CRM::PRACOWNIK_KIEROWNIK){
					$this->tLiderzy->setFiltrKeyValueAndAndRead(array('stanowisko_id'=>$_SESSION['STANOWISKO_ID']));
					$rLider = $this->tLiderzy->getRecordIfOne();
					$this->tZespoly->setFiltrKeyValueAndAndRead(array('lider_id'=>$rLider->getId()));
				}
			}
			for($rCzlonekZespolu = $this->tZespoly->getRecordFirst(); $rCzlonekZespolu !== null; $rCzlonekZespolu = $this->tZespoly->getRecordNext()){
				$rStanowisko = $this->tStanowiska->getRecord($rCzlonekZespolu->getEncja()->getStanowiskoId());
				$rPracownik = $this->tPracownicy->getRecord($rStanowisko->getEncja()->getPracownikId());
				$row = array();
				$row['id'] = $rCzlonekZespolu->getId();
				$row['stanowisko_id'] = $rStanowisko->getId();
				$row['stanowisko_symbol'] = $rStanowisko->getEncja()->getSymbol();
				$row['stanowisko_nazwa'] = $rStanowisko->getEncja()->getNazwa();
				$row['stanowisko_tel'] = $rStanowisko->getEncja()->getTel();
				$row['pracownik_id'] = $rPracownik->getId();
				$row['pracownik_nazwisko'] = $rPracownik->getEncja()->getNazwisko();
				$row['pracownik_imie'] = $rPracownik->getEncja()->getImie();
				$row['data_od'] = $rCzlonekZespolu->getEncja()->getDataOd();
				$row['data_do'] = $rCzlonekZespolu->getEncja()->getDataDo();
				$this->dataOut[] = $row;
			}
		} catch (\Exception $ex) {
			$this->success = FALSE;
			$this->catchException($ex);
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
    return json_encode(array('success'=>  $this->success, 'totalCount'=> count($this->dataOut), 'count'=>  count($this->dataOut), 'data'=>  $this->dataOut));
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
	 * @var LiderzyTable
	 */
	protected $tLiderzy;
	/**
	 * @var ZespolyTable
	 */
	protected $tZespoly;
}