<?php
/**
 * @package crmsw
 * @subpackage logic
 * @author Piotr Janczura <piotr@janczura.pl>
 * @task 2014-10-20 Przebudowa Table do obsługi zapytań preparowanych
 * @task 2014-10-20 Duża przebudowa DependencyTableRecord
 * @todo \pjpl\a\BusinessLogic <<< \crmsw\lib\a\BusinessLogic z usunięciem dziesdziczenia po klasie ...\beta\BusinessLogic
 */
class L_BankiOddzialyCreate extends \crmsw\lib\a\BusinessLogic{
  public function __construct() {
    parent::__construct();
    $this->BankiOddzialyTable = $this->DB->tableBankiOddzialy();
  }

  protected function logic() {
    foreach ($this->dataIn as $key => $row) {
      try{
        $BankOddzial = new BankOddzial(
                $row['bank_id'],
                $row['symbol'],
                $row['nazwa'],
                $row['nip'],
                $row['kod_poczt'],
                $row['miejscowosc'],
                $row['ul'],
                $row['nr_b'],
                $row['nr_l'],
                $row['tel'],
                $row['email'],
                $row['data_od'],
								null
                );
        $this->dataOut[$key] = array('id'=>$this->BankiOddzialyTable->createRecordImmediately($BankOddzial)->getId(),'tmpId'=>  $row['tmpId']);
      }  catch (Exception $E){
				$this->success = FALSE;
        $this->catchLogicException($E);
      }
    }
  }
  public function fromRequest(&$_request) {
		$data = json_decode($_request,true);
		foreach ($data['data'] as $key => $value) {
			$row = array();
			if(isset($value['tmpId'])){
				$row['tmpId'] = $this->Firewall->login($value['tmpId']);
			}  else {
				$row['tmpId'] = null;
			}
			if(isset($value['bank_id'])){
				$row['bank_id'] = $this->Firewall->word($value['bank_id']);
			}else{
				$row['bank_id'] = '';
			}
			if(isset($value['symbol'])){
				$row['symbol'] = $this->Firewall->word($value['symbol']);
			}else{
				$row['symbol'] = '';
			}
			if(isset($value['nazwa'])){
				$row['nazwa'] = $this->Firewall->string($value['nazwa']);
			}else{
				$row['nazwa'] = '';
			}
			if(isset($value['nip'])){
				$row['nip'] = $this->Firewall->serialNumber($value['nip']);
			}else{
				$row['nip'] = '';
			}
			if(isset($value['kod_poczt'])){
				$row['kod_poczt'] = $this->Firewall->serialNumber($value['kod_poczt']);
			}else{
				$row['kod_poczt'] = '';
			}
			if(isset($value['miejscowosc'])){
				$row['miejscowosc'] = $this->Firewall->string($value['miejscowosc']);
			}else{
				$row['miejscowosc'] = '';
			}
			if(isset($value['ul'])){
				$row['ul'] = $this->Firewall->string($value['ul']);
			}else{
				$row['ul'] = '';
			}
			if(isset($value['nr_b'])){
				$row['nr_b'] = $this->Firewall->string($value['nr_b']);
			}else{
				$row['nr_b'] = '';
			}
			if(isset($value['nr_l'])){
				$row['nr_l'] = $this->Firewall->string($value['nr_l']);
			}else{
				$row['nr_l'] = '';
			}
			if(isset($value['tel'])){
				$row['tel'] = $this->Firewall->telefonNumber($value['tel']);
			}else{
				$row['tel'] = '';
			}
			if(isset($value['email'])){
				$row['email'] = $this->Firewall->email($value['email']);
			}else{
				$row['email'] = '';
			}
			if(isset($value['data_od'])){
				$row['data_od'] = $this->Firewall->date($value['data_od']);
			}else{
				$row['data_od'] = date('Y-m-d');
			}
			$this->dataIn[$key] = $row;
    }
  }
  /**
   * @var BankiOddzialyTable
   */
  protected $BankiOddzialyTable;
}