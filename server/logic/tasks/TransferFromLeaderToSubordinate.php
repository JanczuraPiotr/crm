<?php
namespace crmsw\logic\tasks;
use crmsw\lib\a\BusinessLogic;
/**
 * Przekazuje zadanie przez lidera do jego podwładnego.
 * Parametrami są identyfikator stanowiska pracy podładnego, nr przekazywanego zadania i notatka związana z przekazaniem
 * @package crmsw
 * @subpackage database
 * @author Piotr Janczura <piotr@janczura.pl>
 * @done 2014-09-22
 */
class TransferFromLeaderToSubordinate extends BusinessLogic{
  public function __construct() {
    parent::__construct();
    $this->tZespoly = $this->DB->tableZespoly();
		$this->tLiderzy = $this->DB->tableLiderzy();
		$this->tPracownicy = $this->DB->tablePracownicy();
		$this->tStanowiska = $this->DB->tableStanowiska();
  }

  protected function logic() {
		$dataTmp = array();
		$ZadanieNextStep = new NextStep();
    foreach ($this->dataIn as $key => $row) {
      try{
				// Zalogowany jest lider. Sprawdzam zespół jakiemu przewodzi
				$this->tLiderzy->__DEPRECIATE__setFiltrKeyValueAndAndRead(array('stanowisko_id'=>(int)$_SESSION['STANOWISKO_ID'],'data_do'=>NULL));// @depreciate
				$rLider = $this->tLiderzy->getRecordIfOne();
				$rStanowisko = $this->tStanowiska->getRecord($rLider->getEncja()->getStanowiskoId());
				$rPracownik = $this->tPracownicy->getRecord($rStanowisko->getEncja()->getPracownikId());
				$row['stanowisko_id'] = $row['stanowisko_id'];
				$row['notatka'] = '{przekazanie od : '.$rPracownik->getEncja()->getNazwisko().' '.$rPracownik->getEncja()->getImie().' ['.$rLider->getEncja()->getNazwa().']}<br/>'.$row['notatka'];
				$row['data_step'] = date('Y-m-d H:i:s');
				$dataTmp[$key] = $row;
      } catch (\Exception $E) {
				$this->success = true;
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
			$this->return_code = E::ERR_UNKNOWN;
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
		if(isset($we['stanowisko_id'])){
			$row['stanowisko_id'] = $this->Firewall->int($we['stanowisko_id']);
		}else{
			$row['stanowisko_id'] = NULL;
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