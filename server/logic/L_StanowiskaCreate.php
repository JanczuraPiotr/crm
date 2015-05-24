<?php
/**
 * @package crmsw
 * @subpackage logic
 * @author Piotr Janczura <piotr@janczura.pl>
 * @todo \pjpl\a\BusinessLogic <<< \crmsw\lib\a\BusinessLogic z usunięciem dziesdziczenia po klasie ...\beta\BusinessLogic
 * @err 2014-09-22 Nie działa tworzenie stanowisk pracy ze względu na błędy kluczy obcych
 */
class L_StanowiskaCreate extends \crmsw\lib\a\BusinessLogic{
  public function __construct() {
    parent::__construct();
    $this->StanowiskaTable = $this->DB->tableStanowiska();
  }
  protected function logic() {
    foreach ($this->dataIn as $key => $row) {
      try{
				if($row['status_stanowiska_id'] === 1 ){ // 1 jest identyfikatorem prezesa zarządu
					// Stabowisko ma otrzymać status prezesa zarządy w tej firmie.
					// Należy się upewnić czy nie jest to próba utworzenia drugiego prezesa w firmie.
					$rStanowisko = $this->tStanowiska->getRecord($row['id']);
					$eStanowisko = $rStanowisko->getEncja();
					if($eStanowisko->getStatusStanowiskaId() === $row['status_stanowiska_id']){
						// Aktualizowane stanowisko "jest prezesem" nie ma więc obawy, że obecna aktualizacja jest próbą dodania nowego prezesa
						$this->create($key,$row);
					}else{
						// Aktualizowane stanowisko nie jest prezesem. Należy sprawdzić czy aktualizacja nie spowoduje dodania kolejnego prezesa.
						$tFirmyOddzaly = $this->DB->tableFirmyOddzialy();
						$rFirmaOddzial = $tFirmyOddzaly->getRecord($eStanowisko->getPlacowkaId());
						$eFirmaOddzial = $rFirmaOddzial->getEncja();
						$tFirmyOddzaly->/*@depreciate*/ __DEPRECIATE__setFiltrKeyValueAndAndRead(array('firma_id'=>$eFirmaOddzial->getFirmaId()));
						for($rFirmaOddzial = $tFirmyOddzaly->getRecordFirst(); $rFirmaOddzial !== NULL; $rFirmaOddzial = $tFirmyOddzaly->getRecordNext()){
							$this->tStanowiska->__DEPRECIATE__setFiltrKeyValueAndAndRead(array('placowka_id'=>$rFirmaOddzial->getId(),'status_stanowiska_id'=>1 ));// @depreciate
							if($this->tStanowiska->count() > 0){
								$kolejny_prezes = true;
								break;
							}
						}
						if(!$kolejny_prezes){
							$this->create($key,$row);
						}else{
							throw new pjpl\depreciate\EDBNotUnique(
											__CLASS__,
											__FUNCTION__,
											$this->tStanowiska->getDI()->tableName(),
											'status_stanowiska_id',
											$row['status_stanowiska_id'],
											'',
											'Próba utworzenia kolejnego prezesa w spółce'
											);
						}
					}
				}else{
					$this->create($key,$row);
				}
      }  catch (\Exception $E){
				$this->success = FALSE;
        $this->catchLogicException($E);
      }
    }
  }
	private function create($key,$row){
		$Stanowisko = \Stanowisko::create([
						'symbol'               => $row['symbol'],
						'nazwa'                => $row['nazwa'],
						'placowka_id'          => $row['placowka_id'],
						'pracownik_id'         => null, // $row['pracownik_id'], Przypisywanie pracownika możliwa tylko przez logikę zwalaniania i zatrudniania na stanowisku
						'tel'                  => $row['tel'],
						'email'                => $row['email'],
						'status_stanowiska_id' => $row['status_stanowiska_id'],
						'data_od'              => $row['data_od'],
						'data_dp'              => null
						]
		);
		$this->dataOut[$key] = array('id'=>$this->StanowiskaTable->createRecordImmediately($Stanowisko)->getId(),'tmpId'=>  $row['tmpId']);
	}
  public function fromRequest(&$_request) {
		$we = json_decode($_request, TRUE);
		foreach ($we['data'] as $key => $value) {
			$row =array();
			if(isset($value['tmpId'])){
				$row['tmpId'] = $this->Firewall->login($value['tmpId']);
			}  else {
				$row['tmpId'] = null;
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
			$this->dataIn[$key] = $row;
    }
  }
  /**
   * @var StanowiskaTable
   */
  public $StanowiskaTable;
}