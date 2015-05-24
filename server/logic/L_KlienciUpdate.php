<?php
/**
 * @package crmsw
 * @subpackage logic
 * @author Piotr Janczura <piotr@janczura.pl>
 * @confirm 2014-12-29
 * @todo \pjpl\a\BusinessLogic <<< \crmsw\lib\a\BusinessLogic z usunięciem dziesdziczenia po klasie ...\beta\BusinessLogic
 */
class L_KlienciUpdate extends \crmsw\lib\a\BusinessLogic{
  public function __construct() {
    parent::__construct();
    $this->KlienciTable = $this->DB->tableKlienci();
  }
  protected function logic() {
    foreach ($this->dataIn as $key => $row) {
			try{
				$Record = $this->KlienciTable->getRecord($row['id']);
				$Klient = $Record->getEncja();
				if($row['nazwa']){                   $Klient->nazwa                   = $row['nazwa'];}
				if($row['imie']){	                   $Klient->imie                    = $row['imie'];}
				if($row['pesel']){                   $Klient->pesel                   = $row['pesel'];}
				if($row['nip']){                     $Klient->nip                     = $row['nip'];}
				if($row['kod_poczt']){               $Klient->kod_poczt               = $row['kod_poczt'];}
				if($row['miejscowosc']){             $Klient->miejscowosc             = $row['miejscowosc'];}
				if($row['ul']){                      $Klient->ul                      = $row['ul'];}
				if($row['nr_b']){                    $Klient->nr_b                    = $row['nr_b'];}
				if($row['nr_l']){                    $Klient->nr_l                    = $row['nr_l'];}
				if($row['email']){                   $Klient->email                   = $row['email'];}
				if($row['telkom']){                  $Klient->telkom                  = $row['telkom'];}
				if($row['teldom']){                  $Klient->teldom                  = $row['teldom'];}
				if($row['telpraca']){                $Klient->telpraca                = $row['telpraca'];}
				if($row['opis']){                    $Klient->opis                    = $row['opis'];}
				if($row['pochodzenie_klientow_id']){ $Klient->pochodzenie_klientow_id = $row['pochodzenie_klientow_id'];}
				if($row['statusy_klientow_id']){		 $Klient->statusy_klientow_id     = $row['statusy_klientow_id'];}
				if($row['firma_id']){                $Klient->firma_id                = $row['firma_id'];}
				if($row['data_od']){                 $Klient->data_od                 = $row['data_od'];}
				if($row['data_do']){                 $Klient->data_do                 = $row['data_do'];}
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
			if(isset($value['nazwa'])){
				$row['nazwa'] = $this->Firewall->string($value['nazwa']);
			}else{
				$row['nazwa'] = null;
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
			if(isset($value['nip'])){
				$row['nip'] = $this->Firewall->serialNumber($value['nip']);
			}else{
				$row['nip'] = null;
			}
			if(isset($value['kod_poczt'])){
				$row['kod_poczt'] = $this->Firewall->serialNumber($value['kod_poczt']);
			}else{
				$row['kod_poczt'] = null;
			}
			if(isset($value['miejscowosc'])){
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
			if(isset($value['email'])){
				$row['email'] = $this->Firewall->email($value['email']);
			}else{
				$row['email'] = null;
			}
			if(isset($value['telkom'])){
				$row['telkom'] = $this->Firewall->telefonNumber($value['telkom']);
			}else{
				$row['telkom'] = null;
			}
			if(isset($value['teldom'])){
				$row['teldom'] = $this->Firewall->telefonNumber($value['teldom']);
			}else{
				$row['teldom'] = null;
			}
			if(isset($value['telpraca'])){
				$row['telpraca'] = $this->Firewall->telefonNumber($value['telpraca']);
			}else{
				$row['telpraca'] = null;
			}
			if(isset($value['opis'])){
				$row['opis'] = $this->Firewall->string($value['opis']);
			}else{
				$row['opis'] = null;
			}
			if(isset($value['pochodzenie_klientow_id'])){
				$row['pochodzenie_klientow_id'] = $this->Firewall->int($value['pochodzenie_klientow_id']);
			}else{
				$row['pochodzenie_klientow_id'] = null;
			}
			if(isset($value['statusy_klientow_id'])){
				$row['statusy_klientow_id'] = $this->Firewall->int($value['statusy_klientow_id']);
			}else{
				$row['statusy_klientow_id'] = null;
			}
			if(isset($value['firma_id'])){
				$row['firma_id'] = $this->Firewall->int($value['firma_id']);
			}else{
				$row['firma_id'] = null;
			}
			if(isset($value['data_od'])){
				$row['data_od'] = $this->Firewall->date($value['data_od']);
			}else{
				$row['data_od'] = null;
			}
			if(isset($value['data_do'])){
				$row['data_do'] = $this->Firewall->date($value['data_do']);
			}else{
				$row['data_do'] = null;
			}
			$this->dataIn[$key] = $row;
		}
  }
  /**
   * @var KlienciTable
   */
  protected $KlienciTable;
}