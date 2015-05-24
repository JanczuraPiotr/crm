<?php
/**
 * @package crmsw
 * @subpackage logic
 * @author Piotr Janczura <piotr@janczura.pl>
 * @confirm 2014-12-24
 * @todo \pjpl\a\BusinessLogic <<< \crmsw\lib\a\BusinessLogic z usunięciem dziesdziczenia po klasie ...\beta\BusinessLogic
 */
class L_FirmyOddzialyUpdate extends \crmsw\lib\a\BusinessLogic{
  public function __construct() {
    parent::__construct();
    $this->FirmyOddzialyTable = $this->DB->tableFirmyOddzialy();
  }
  protected function logic() {
    foreach ($this->dataIn as $key => $row) {
      try{
				$Record = $this->FirmyOddzialyTable->getRecord($row['id']);
				$FirmaOddzial = $Record->getEncja();
				if( $row['firma_id'] )    { $FirmaOddzial->firma_id    = $row['firma_id']; }
				if( $row['symbol'] )      { $FirmaOddzial->symbol      = $row['symbol']; }
				if( $row['nazwa'] )       { $FirmaOddzial->nazwa       = $row['nazwa']; }
				if( $row['nip'] )         { $FirmaOddzial->nip         = $row['nip']; }
				if( $row['kod_poczt'] )   { $FirmaOddzial->kod_poczt   = $row['kod_poczt']; }
				if( $row['miejscowosc'] ) { $FirmaOddzial->miejscowosc = $row['miejscowosc']; }
				if( $row['ul'] )          { $FirmaOddzial->ul          = $row['ul']; }
				if( $row['nr_b'] )        { $FirmaOddzial->nr_b        = $row['nr_b']; }
				if( $row['nr_l'] )        { $FirmaOddzial->nr_l        = $row['nr_l']; }
				if( $row['tel'] )         { $FirmaOddzial->tel         = $row['tel']; }
				if( $row['email'] )       { $FirmaOddzial->email       = $row['email']; }
				if( $row['data_od'] )     { $FirmaOddzial->data_od     = $row['data_od']; }
				if( $row['data_do'] )     { $FirmaOddzial->data_do     = $row['data_do']; }
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
			if(isset($value['firma_id'])){
				$row['firma_id'] = $this->Firewall->int($value['firma_id']);
			}else{
				$row['firma_id'] = null;
			}
			if(isset($value['symbol'])){
				$row['symbol'] = $this->Firewall->word($value['symbol']);
			}else{
				$row['symbol'] = null;
			}
			if(isset($value['nazwa'])){
				$row['nazwa'] = $this->Firewall->string($value['nazwa']);
			}else{
				$row['nazwa'] = NULL;
			}
			if(isset($value['nip'])){
				$row['nip'] = $this->Firewall->serialNumber($value['nip']);
			}else{
				$row['nip'] = NULL;
			}
			if(isset($value['kod_poczt'])){
				$row['kod_poczt'] = $this->Firewall->serialNumber($value['kod_poczt']);
			}else{
				$row['kod_poczt'] = NULL;
			}
			if(isset($value['miejscowosc'])){
				$row['miejscowosc'] = $this->Firewall->string($value['miejscowosc']);
			}else{
				$row['miejscowosc'] = NULL;
			}
			if(isset($value['ul'])){
				$row['ul'] = $this->Firewall->string($value['ul']);
			}else{
				$row['ul'] = NULL;
			}
			if(isset($value['nr_b'])){
				$row['nr_b'] = $this->Firewall->string($value['nr_b']);
			}else{
				$row['nr_b'] =NULL;
			}
			if(isset($value['nr_l'])){
				$row['nr_l'] = $this->Firewall->string($value['nr_l']);
			}else{
				$row['nr_l'] = NULL;
			}
			if(isset($value['tel'])){
				$row['tel'] = $this->Firewall->telefonNumber($value['tel']);
			}else{
				$row['tel'] = NULL;
			}
			if(isset($value['email'])){
				$row['email'] = $this->Firewall->email($value['email']);
			}else{
				$row['email'] = NULL;
			}
			if(isset($value['data_od'])){
				$row['data_od'] = $this->Firewall->date($value['data_od']);
			}else{
				$row['data_od'] = NULL;
			}
			if(isset($value['data_do'])){
				$row['data_do'] = $this->Firewall->date($value['data_do']);
			}  else {
				$row['data_do'] = NULL;
			}
			$this->dataIn[$key] = $row;
    }
  }
  /**
   * @var \FirmyOddzialyTable
   */
  protected $FirmyOddzialyTable;

}