<?php
namespace crmsw\logic\tasks;
use crmsw\lib\a\BusinessLogic;
use pjpl\e\a\E;
/**
 * Przekazuje zadanie wykonywane przez pracownika do jego lidera.
 * Parametrami zewnętrznmi są nr_zadania i notatka związana z przekazaniem zadania - informująca o powodzie przekazania.
 * @package crmsw
 * @subpackage logic
 * @author Piotr Janczura <piotr@janczura.pl>
 * @done 2014-09-22
 */
class TransferFromSubordinateToLeader extends BusinessLogic{
  public function __construct() {
    parent::__construct();
    $this->tZespoly = $this->DB->tableZespoly();
		$this->tLiderzy = $this->DB->tableLiderzy();
		$this->tPracownicy = $this->DB->tablePracownicy();
		$this->tStanowiska = $this->DB->tableStanowiska();
  }

  protected function logic() {
		$dataTmp = array();
    foreach ($this->dataIn as $key => $row) {
      try{
				// Szukam lidera dla stanowiska obsługującego zadanie.
				$this->tZespoly->__DEPRECIATE__setFiltrKeyValueAndAndRead(array('stanowisko_id'=>(int)$_SESSION['STANOWISKO_ID'],'data_do'=>NULL));// @depreciate
				$rCzlonekZespolu = $this->tZespoly->getRecordIfOne();
				// @todo Sprawdz czy powstał obiekt $rCzlonekZespolu
				$rLider = $this->tLiderzy->getRecord($rCzlonekZespolu->getEncja()->getLiderId());
				$rStanowisko = $this->tStanowiska->getRecord((int)$_SESSION['STANOWISKO_ID']);
				$rPracownik = $this->tPracownicy->getRecord($rStanowisko->getEncja()->getPracownikId());
				$row['stanowisko_id'] = $rLider->getEncja()->getStanowiskoId();
				$row['notatka'] = '{przekazanie od : '.$rPracownik->getEncja()->getNazwisko().' '.$rPracownik->getEncja()->getImie().' ['.$rLider->getEncja()->getNazwa().']}<br/>'.$row['notatka'];
				$row['data_step'] = date('Y-m-d H:i:s');
				$dataTmp[$key] = $row;
      } catch (\Exception $E) {
				$this->success = false;
      }
    }
		try{
			if($this->success){
				$ZadanieNextStep = new NextStep();
				$ZadanieNextStep->internalCall($dataTmp);
				$this->dataOut = $ZadanieNextStep->getDataOut();
			}
		}  catch (\Exception $e){
			$this->success = false ;
			$this->return_code = E::UNKNOWN;
			$this->return_msg = 'E::ERR_UNKNOWN';
		}
	}
  public function fromRequest(&$_request) {
		$we = json_decode($_request['data'],TRUE);
		$row = array();
		if(isset($we['nr_zadania'])){
			$row['nr_zadania'] = $this->Firewall->int($we['nr_zadania']);
		}else{
			$row['nr_zadania'] = NULL;
		}
		if(isset($we['notatka'])){
			$row['notatka'] = $this->Firewall->string($we['notatka']);
		}else{
			$row['notatka'] = NULL;
		}
		$this->dataIn[] = $row;
  }
	/**
	 * @var \ZespolyTable
	 */
	protected $tZespoly;
	/**
	 * @var \LiderzyTable
	 */
	protected $tLiderzy;
	/**
	 * @var \PracownicyTable
	 */
	protected $tPracownicy;
	/**
	 * @var \StanowiskaTable
	 */
	protected $tStanowiska;
}