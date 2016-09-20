<?php
/**
 * @package crmsw
 * @subpackage logic
 * @author Piotr Janczura <piotr@janczura.pl>
 * @task 2014-10-20 Przebudowa Table do obsługi zapytań preparowanych
 * @task 2014-10-20 Duża przebudowa DependencyTableRecord
 * @todo \pjpl\a\BusinessLogic <<< \crmsw\lib\a\BusinessLogic z usunięciem dziesdziczenia po klasie ...\beta\BusinessLogic
 */

class L_WiazanieFirmBankowCreate extends \crmsw\lib\a\BusinessLogic{
	public function __construct() {
		parent::__construct();
		$this->BankiOddzialyFirmyOddzialyTable = $this->DB->tableBankiOddzialyFirmyOddzialy();
	}
	protected function logic() {
		/**
		 * @todo Uwzględnić limity wczytywanych rekordów
		 */
    foreach ($this->dataIn as $key => $row) {
      try{
				$this->BankiOddzialyFirmyOddzialyTable->__DEPRECIATE__setFiltrKeyValueAndAndRead(array('firma_oddzial_id'=>$row['firma_oddzial_id'],'bank_oddzial_id'=>$row['bank_oddzial_id'],'data_do'=>null));// @depreciate
				if($this->BankiOddzialyFirmyOddzialyTable->count() > 0){
					$this->dataOut[$key] = array('success'=>false, 'code'=>ERR_EDB_NOTUNIQUE, 'id'=>null,'tmpId'=>  $row['tmpId']);
					throw $E = new pjpl\depreciate\EDBNotUnique(__CLASS__, __FILE__, $this->BankiOddzialyFirmyOddzialyTable->getDI()->tableName(), 'firma_oddzial_id|bank_oddzial_id', '');
				}else{
					$BankOddzialFirmaOddzial = new BankOddzialFirmaOddzial(
									$row['firma_oddzial_id'],
									$row['bank_oddzial_id'],
									$row['data_od'],
									NULL
									);
					$this->dataOut[$key] = array('id'=>$this->BankiOddzialyFirmyOddzialyTable->createRecordImmediately($BankOddzialFirmaOddzial)->getId(),'tmpId'=>  $row['tmpId']);
				}
      }  catch (\Exception $E){
				$this->success = FALSE;
        $this->catchLogicException($E);
      }
    }
	}
	public function fromRequest(&$_request) {
		$we = json_decode($_request,TRUE);
		foreach ($we['data'] as $key => $value) {
			$row =array();
			if(isset($value['tmpId'])){
				$row['tmpId'] = $this->Firewall->login($value['tmpId']);
			}  else {
				$row['tmpId'] = null;
			}
			if(isset($value['firma_oddzial_id'])){
				$row['firma_oddzial_id'] = $this->Firewall->string($value['firma_oddzial_id']);
			}  else {
				$row['firma_oddzial_id'] = null;
			}
			if(isset($value['bank_oddzial_id'])){
				$row['bank_oddzial_id'] = $this->Firewall->string($value['bank_oddzial_id']);
			}  else {
				$row['bank_oddzial_id'] = null;
			}
			if(isset($value['data_od'])){
				$row['data_od'] = $this->Firewall->date($value['data_od']);
			}  else {
				$row['data_od'] = date('Y-m-d');
			}
			$this->dataIn[] = $row;
		}
	}
	/**
	 * @var BankiOddzialyFirmyOddzialyTable
	 */
	protected $BankiOddzialyFirmyOddzialyTable;
}