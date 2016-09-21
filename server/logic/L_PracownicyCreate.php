<?php
/**
 * @package crmsw
 * @subpackage logic
 * @author Piotr Janczura <piotr@janczura.pl>
 * @task 2014-10-20 Przebudowa Table do obsługi zapytań preparowanych
 * @task 2014-10-20 Duża przebudowa DependencyTableRecord
 * @todo \pjpl\a\BusinessLogic <<< \crmsw\lib\a\BusinessLogic z usunięciem dziesdziczenia po klasie ...\beta\BusinessLogic
 */

class L_PracownicyCreate extends \crmsw\lib\a\BusinessLogic{
  public function __construct() {
    parent::__construct();
    $this->PracownicyTable = $this->DB->tablePracownicy();
  }

  protected function logic() {
    foreach ($this->dataIn as $key => $row) {
      try{
				$ePracownik = \Pracownik::create($row);
				$rPracownik = $this->PracownicyTable->createRecordImmediately($ePracownik);
				$this->dataOut[$key] = array('id'=>$rPracownik->getId(),'tmpId'=>  $row['tmpId'], 'rec' => $rPracownik->toArray());
      }  catch (\Exception $E){
				$this->success = FALSE;
        $this->catchLogicException($E);
      }
    }
  }

  public function fromRequest(&$_request) {
		$we = json_decode($_request, TRUE);
		foreach ($we['data'] as $key => $value) {
			$row = array();
			if(isset($value['tmpId'])){
				$row['tmpId'] = $this->Firewall->login($value['tmpId']);
			}  else {
				$row['tmpId'] = null;
			}
			if(isset($value['firma_id'])){
				$row['firma_id'] = $this->Firewall->login($value['firma_id']);
			}  else {
				$row['firma_id'] = null;
			}
			if(isset($value['nazwisko'])){
				$row['nazwisko'] = $this->Firewall->string($value['nazwisko']);
			}else{
				$row['nazwisko'] = null;
			}
			if(isset($value['imie'])){
				$row['imie'] = $this->Firewall->string($value['imie']);
			}else{
				$row['imie'] = null;
			}
			if(isset($value['pesel'])){
				$row['pesel'] = $this->Firewall->serialNumber($value['pesel']);
			}else{
				$row['pesel'] = null;
			}
			if(isset($value['kod_poczt'])){
				$row['kod_poczt'] = $this->Firewall->serialNumber($value['kod_poczt']);
			}else{
				$row['kod_poczt'] = null;
			}
			if($value['miejscowosc']){
				$row['miejscowosc'] = $this->Firewall->string($value['miejscowosc']);
			}else{
				$row['miejscowosc'] = null;
			}
			if(isset($value['ul'])){
				$row['ul'] = $this->Firewall->string($value['ul']);
			}else{
				$row['ul'] = null;
			}
			if(isset($value['nr_b'])){
				$row['nr_b'] = $this->Firewall->string($value['nr_b']);
			}else{
				$row['nr_b'] = null;
			}
			if(isset($value['nr_l'])){
				$row['nr_l'] = $this->Firewall->string($value['nr_l']);
			}else{
				$row['nr_l'] = null;
			}
			if(isset($value['tel'])){
				$row['tel'] = $this->Firewall->telefonNumber($value['tel']);
			}else{
				$row['tel'] = null;
			}
			if(isset($value['email'])){
				$row['email'] = $this->Firewall->email($value['email']);
			}else{
				$row['email'] = null;
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
   * @var \PracownicyTable
   */
  protected $PracownicyTable;
}