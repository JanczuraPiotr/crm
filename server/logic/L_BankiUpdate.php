<?php
/**
 * @package crmsw
 * @subpackage database
 * @author Piotr Janczura <piotr@janczura.pl>
 * @task 2014-10-20 Przebudowa Table do obsługi zapytań preparowanych
 * @task 2014-10-20 Duża przebudowa DependencyTableRecord
 * @todo \pjpl\a\BusinessLogic <<< \crmsw\lib\a\BusinessLogic z usunięciem dziesdziczenia po klasie ...\beta\BusinessLogic
 */
class L_BankiUpdate extends \crmsw\lib\a\BusinessLogic{
  public function __construct() {
    parent::__construct();
    $this->BankiTable = $this->DB->tableBanki();
  }
  protected function logic() {
    foreach ($this->dataIn as $key => $row) {
      try{
				$Record = $this->BankiTable->getRecord($row['id']);
				$Bank = $Record->getEncja();
				$Bank->setSymbol($row['symbol']);
				$Bank->setNazwa($row['nazwa']);
				$Bank->setNip($row['nip']);
				$Bank->setKodPoczt($row['kod_poczt']);
				$Bank->setMiejscowosc($row['miejscowosc']);
				$Bank->setUl($row['ul']);
				$Bank->setNrB($row['nr_b']);
				$Bank->setNrL($row['nr_l']);
				$Bank->setTel($row['tel']);
				$Bank->setEmail($row['email']);
				$Bank->setDataOd($row['data_od']);
				$Bank->setDataDo($row['data_do']);
        $Record->updateImmediately();
        $this->dataOut[$key] = array('success'=>true,'id'=>$row['id']);
      }catch (Exception $E){
        $this->success = FALSE;
        $this->catchLogicException($E);
      }
    }
  }

  public function fromRequest(&$_request) {
    $we = json_decode($_request, TRUE);
		foreach ($we['data'] as $key => $value) {
			$row = array();
			if(isset($value['id'])){
				$row['id'] = $this->Firewall->int($value['id']);
			}else{
				$row['id'] = null;
			}
			if(isset($value['symbol'])){
				$row['symbol'] = $this->Firewall->word($value['symbol']);
			}else{
				$row['symbol'] = null;
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
				$row['data_od'] = '';
			}
			if(isset($value['data_do'])){
				$row['data_do'] = $this->Firewall->date($value['data_do']);
			}  else {
				$row['data_do'] = '';
			}
			$this->dataIn[$key] = $row;
    }
  }
  /**
   * @var BankiTable
   */
  protected $BankiTable;
}