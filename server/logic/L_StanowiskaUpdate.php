<?php
/**
 * @package crmsw
 * @subpackage database
 * @author Piotr Janczura <piotr@janczura.pl>
 * @prace 2014-10-20 Przebudowa Table do obsługi zapytań preparowanych
 * @prace 2014-10-20 Duża przebudowa DependencyTableRecord
 * @todo \pjpl\a\BusinessLogic <<< \crmsw\lib\a\BusinessLogic z usunięciem dziesdziczenia po klasie ...\beta\BusinessLogic
 */
class L_StanowiskaUpdate extends \crmsw\lib\a\BusinessLogic{
	public function __construct() {
		parent::__construct();
		$this->tStanowiska = $this->DB->tableStanowiska();
	}

	protected function logic() {
		$kolejny_prezes = false;
		/**
		 * @todo Przerowbić korzystanie z status_stanowiska_id na kod stanowiska
		 */
		foreach ($this->dataIn as $key => $row) {
			try{
//				if($row['status_stanowiska_id'] === 1 ){ // 1 jest identyfikatorem prezesa zarządu
//					// Stabowisko ma otrzymać status prezesa zarządy w tej firmie.
//					// Należy się upewnić czy nie jest to próba utworzenia drugiego prezesa w firmie.
//					$rStanowisko = $this->tStanowiska->getRecord($row['id']);
//					$eStanowisko = $rStanowisko->getEncja();
//					if($eStanowisko->getStatusStanowiskaId() === $row['status_stanowiska_id']){
//						// Aktualizowane stanowisko "jest prezesem" nie ma więc obawy, że obecna aktualizacja jest próbą dodania nowego prezesa
//						$this->update($key,$row);
//					}else{
//						// Aktualizowane stanowisko nie jest prezesem. Należy sprawdzić czy aktualizacja nie spowoduje dodania kolejnego prezesa.
//						$tFirmyOddzaly = $this->DB->tableFirmyOddzialy();
//						$rFirmaOddzial = $tFirmyOddzaly->getRecord($eStanowisko->getPlacowkaId());
//						$eFirmaOddzial = $rFirmaOddzial->getEncja();
//						$tFirmyOddzaly->setFiltrKeyValueAndAndRead(array('firma_id'=>$eFirmaOddzial->getFirmaId()));
//						for($rFirmaOddzial = $tFirmyOddzaly->getRecordFirst(); $rFirmaOddzial !== NULL; $rFirmaOddzial = $tFirmyOddzaly->getRecordNext()){
//							$this->tStanowiska->setFiltrKeyValueAndAndRead(array('placowka_id'=>$rFirmaOddzial->getId(),'status_stanowiska_id'=>1 ));
//							if($this->tStanowiska->count() > 0){
//								$kolejny_prezes = true;
//								break;
//							}
//						}
//						if(!$kolejny_prezes){
//							$this->update($key,$row);
//						}else{
//							throw $e = new pjpl\depreciate\NotUnique(
//											__CLASS__,
//											__FUNCTION__,
//											$this->tStanowiska->getDI()->tableName(),
//											'status_stanowiska_id',
//											$row['status_stanowiska_id'],
//											'',
//											'Próba utworzenia kolejnego prezesa w spółce'
//											);
//						}
//					}
//				}else{
//					$this->update($key,$row);
//				}
				$this->update($key, $row);
			} catch (\Exception $E) {
				$this->success = FALSE;
				$this->catchLogicException($E);
			}
		}
	}
	private function update($key,$row){
		$Record = $this->tStanowiska->getRecord($row['id']);
		$Stanowisko = $Record->getEncja();
		$Stanowisko->setSymbol($row['symbol']);
		$Stanowisko->setNazwa($row['nazwa']);
		//$Stanowisko->setPlacowkaId($row['placowka_id']); Prznoszenie stanowiska między placówkami nie jest możliwe
		//$Stanowisko->setPracownikId($row['pracownik_id']); Zmiana pracownika możliwa tylko przez logikę zwalaniania i zatrudniania na stanowisku
		$Stanowisko->setTel($row['tel']);
		$Stanowisko->setEmail($row['email']);
		$Stanowisko->setStatusStanowiskaId($row['status_stanowiska_id']);
		$Stanowisko->setDataOd($row['data_od']);
		$Stanowisko->setDataDo($row['data_do']);
		$Record->updateImmediately();
		$this->dataOut[$key] = array('success'=>true,'id'=>$row['id']);
	}
	public function fromRequest(&$_request) {
		$we = json_decode($_request, TRUE);
		foreach ($we['data'] as $key => $value) {
			$row =array();
			if(isset($value['id'])){
				$row['id'] = $this->Firewall->string($value['id']);
			}  else {
				$row['id'] = null;
			}
			if(isset($value['symbol'])){
				$row['symbol'] = $this->Firewall->string($value['symbol']);
			}  else {
				$row['symbol'] = null;
			}
			if(isset($value['nazwa'])){
				$row['nazwa'] = $this->Firewall->string($value['nazwa']);
			}  else {
				$row['nazwa'] = null;
			}
			if(isset($value['placowka_id'])){
				$row['placowka_id'] = $this->Firewall->int($value['placowka_id']);
			}  else {
				$row['placowka_id'] = null;
			}
			if(isset($value['pracownik_id'])){
				$row['pracownik_id'] = $this->Firewall->int($value['pracownik_id']);
			}  else {
				$row['pracownik_id'] = null;
			}
			if(isset($value['tel'])){
				$row['tel'] = $this->Firewall->telefonNumber($value['tel']);
			}  else {
				$row['tel'] = null;
			}
			if(isset($value['email'])){
				$row['email'] = $this->Firewall->email($value['email']);
			}  else {
				$row['email'] = null;
			}
			if(isset($value['status_stanowiska_id'])){
				$row['status_stanowiska_id'] = $this->Firewall->int($value['status_stanowiska_id']);
			}  else {
				$row['status_stanowiska_id'] = 0;
			}
			if(isset($value['data_od'])){
				$row['data_od'] = $this->Firewall->date($value['data_od']);
			}  else {
				$row['data_od'] = date('Y-m-d');
			}
			if(isset($value['data_do'])){
				$row['data_do'] = $this->Firewall->date($value['data_do']);
			}  else {
				$row['data_do'] = NULL;
			}
			if(isset($value['data_zatrudnienia'])){
				$row['data_zatrudnienia'] = $this->Firewall->date($value['data_zatrudnienia']);
			}  else {
				$row['data_zatrudnienia'] = date('Y-m-d');
			}
			if(isset($value['data_zwolnienia'])){
				$row['data_zwolnienia'] = $this->Firewall->date($value['data_zwolnienia']);
			}  else {
				$row['data_zwolnienia'] = NULL;
			}
			$this->dataIn[$key] = $row;
		}
	}
	/**
	 * @var StanowiskaTable
	 */
	protected $tStanowiska;

}