<?php
/**
 * @package crmsw
 * @subpackage logic
 * @author Piotr Janczura <piotr@janczura.pl>
 * @todo \pjpl\a\BusinessLogic <<< \crmsw\lib\a\BusinessLogic z usunięciem dziesdziczenia po klasie ...\beta\BusinessLogic
 */

use pjpl\db\Where;

class L_WiazanieFirmBankowRead extends \crmsw\lib\a\BusinessLogic{
	public function __construct() {
		parent::__construct();
		$this->BankiOddzialyFirmyOddzialyTable = $this->DB->tableBankiOddzialyFirmyOddzialy();
		$this->BankiTable = $this->DB->tableBanki();
		$this->BankiOddzialyTable = $this->DB->tableBankiOddzialy();
	}
	protected function logic() {
		$arrPowiazanie = array();

		if( isset($this->dataIn['filter'][0]['property']) &&  $this->dataIn['filter'][0]['property'] === 'firma_oddzial_id'){
			$firma_oddzial_id = $this->dataIn['filter'][0]['value'];
		}else{
			$firma_oddzial_id = null;
		}
		try{
			$Where = new Where([
					[
							'attribute' => 'firma_oddzial_id',
							'operator'  => '=',
							'value'     => $firma_oddzial_id
					],[
							'attribute' => 'data_do',
							'operator'  => '=',
							'value'     => NULL
					]
			]);
			$this->BankiOddzialyFirmyOddzialyTable->where($Where)->load();
			for( $rPowiazanie = $this->BankiOddzialyFirmyOddzialyTable->getRecordFirst(); $rPowiazanie !== NULL; $rPowiazanie = $this->BankiOddzialyFirmyOddzialyTable->getRecordNext()){
				$rBankOddzial = $this->BankiOddzialyTable->getRecord($rPowiazanie->getEncja()->getBankOddzialId());
				$rBank = $this->BankiTable->getRecord($rBankOddzial->getEncja()->getBankId());
				$this->dataOut[] = array(
								'id'												=>	$rPowiazanie->getId(),
								'bank_id'										=>	$rBank->getId(),
								'firma_oddzial_id'					=>	$rPowiazanie->getEncja()->getFirmaOddzialId(),
								'bank_oddzial_id'						=>	$rPowiazanie->getEncja()->getBankOddzialId(),
								'bank_symbol'								=>	$rBank->getEncja()->getSymbol(),
								'bank_nazwa'								=>	$rBank->getEncja()->getNazwa(),
								'bank_oddzial_symbol'				=>	$rBankOddzial->getEncja()->getSymbol(),
								'bank_oddzial_nazwa'				=>	$rBankOddzial->getEncja()->getNazwa(),
								'bank_oddzial_miejscowosc'	=>	$rBankOddzial->getEncja()->getMiejscowosc(),
								'bank_oddzial_ul'						=>	$rBankOddzial->getEncja()->getUl(),
								'bank_oddzial_nr_b'					=>	$rBankOddzial->getEncja()->getNrB(),
								'bank_oddzial_nr_l'					=>	$rBankOddzial->getEncja()->getNrL(),
								'data_od'										=>	$rPowiazanie->getEncja()->getDataOd(),
								'data_do'										=>	$rPowiazanie->getEncja()->getDataDo()
				);
			}
		} catch (Exception $ex) {
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
		}  else {
			$this->dataIn['filter'] = [];
		}
  }
//	public function getJson(){
//    return json_encode(array('success'=>  $this->success, 'totalCount'=> $this->BankiOddzialyFirmyOddzialyTable->countTotal(), 'count'=>  $this->BankiOddzialyFirmyOddzialyTable->count(), 'data'=>  $this->dataOut));
//	}

	/**
	 * @var BankiOddzialyFirmyOddzialyTable
	 */
	protected $BankiOddzialyFirmyOddzialyTable;
	/**
	 * @var BankiTable
	 */
	protected $BankiTable;
	/**
	 * @var BankiOddzialyTable
	 */
	protected $BankiOddzialyTable;
}