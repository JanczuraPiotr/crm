<?php
/**
 * @package crmsw
 * @subpackage logic
 * @author Piotr Janczura <piotr@janczura.pl>
 * @task 2014-10-20 Przebudowa Table do obsługi zapytań preparowanych
 * @task 2014-10-20 Duża przebudowa DependencyTableRecord
 * @todo \pjpl\a\BusinessLogic <<< \crmsw\lib\a\BusinessLogic z usunięciem dziesdziczenia po klasie ...\beta\BusinessLogic
 */
use pjpl\db\Where;

class L_OddzialyNiepowiazaneRead extends \crmsw\lib\a\BusinessLogic{
	public function __construct() {
		parent::__construct();
		$this->BankiOddzialyFirmyOddzialyTable = $this->DB->tableBankiOddzialyFirmyOddzialy();
		$this->BankiTable = $this->DB->tableBanki();
		$this->BankiOddzialyTable = $this->DB->tableBankiOddzialy();
	}
	protected function logic() {
		$filtr = array();

//		if( isset($filtr['firma_oddzial_id'])){
//			$firma_oddzial_id = $filtr['firma_oddzial_id'];
//		}else{
//			$firma_oddzial_id = null;
//		}
//		if( isset($filtr['bank_id'] )){
//			$bank_id = $filtr['bank_id'];
//		}else{
//			$bank_id = null;
//		}

		try{
			$WhereBankiOddzialy = new Where($this->dataIn['filter']);
			$this->BankiOddzialyTable->where($WhereBankiOddzialy)->load();
			for($rBankOddzial = $this->BankiOddzialyTable->getRecordFirst() ; $rBankOddzial !== null ; $rBankOddzial = $this->BankiOddzialyTable->getRecordNext()){
				$WhereOddzialyOddzialy = new Where([
						[
								'attribute' => 'firma_oddzial_id',
								'operator'  => '=',
								'value'     => $firma_oddzial_id
						],[
								'attribute' => 'bank_oddzial_id',
								'operator'  => '=',
								'value'     => $rBankOddzial->getId()
						],[
								'attribute' => 'data_do',
								'operator'  => '=',
								'value'     => NULL
						]
				]);
				$this->BankiOddzialyFirmyOddzialyTable->where($WhereOddzialyOddzialy)->load();
				if($this->BankiOddzialyFirmyOddzialyTable->count() === 0){
					$this->dataOut[] = $this->BankiOddzialyTable->createRecordImmediately($rBankOddzial->getEncja());
				}else{
					// Jakiś oddział banku jest związany z oddziałem firmy.
					// To znaczy że bank jest związany z oddziałem firmy (a przez to z całą firmą)
					// Nie ma potrzeby tworzyć listy oddziałów banku możliwych do związania z firmą.
					$this->dataOut = array();
					$array = array();
					$array['id'] = '';
					$array['bank_id'] = '';
					$array['symbol'] = '';
					$array['nazwa'] = 'Bank już powiązany';
					$array['nip'] = '';
					$array['kod_poczt'] = '';
					$array['miejscowosc'] = '';
					$array['ul'] = '';
					$array['nr_b'] = '';
					$array['nr_l'] = '';
					$array['tel'] = '';
					$array['email'] = '';
					$array['data_od'] = '';
					$array['data_do'] = '';
					$this->dataOut[] = $array;
					break;
				}
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
			$this->dataIn['filter'] = $this->reformatExtJSFilter($_request['filter']);
		}  else {
			$this->dataIn['filter'] = [];
		}
  }
	public function getJson(){
    return json_encode(array('success'=>  $this->success, 'totalCount'=> count($this->dataOut), 'count'=>  count($this->dataOut), 'data'=>  $this->dataOut));
	}

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