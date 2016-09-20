<?php
/**
 * @package crmsw
 * @subpackage logic
 * @author Piotr Janczura <piotr@janczura.pl>
 * @done 2014-12-29
 * @todo \pjpl\a\BusinessLogic <<< \crmsw\lib\a\BusinessLogic z usunięciem dziesdziczenia po klasie ...\beta\BusinessLogic
 */
class L_FirmyUpdate extends \crmsw\lib\a\BusinessLogic{
  public function __construct() {
    parent::__construct();
    $this->FirmyTable = $this->DB->tableFirmy();
  }
  protected function logic() {
    foreach ($this->dataIn as $key => $row) {
      try{
				$Record = $this->FirmyTable->getRecord($row['id']);
				$Firma = $Record->getEncja();
				if($row['symbol'])      { $Firma->symbol      = $row['symbol']; }
				if($row['nazwa'])       { $Firma->nazwa       = $row['nazwa']; }
				if($row['nip'])         { $Firma->nip         = $row['nip']; }
				if($row['kod_poczt'])   { $Firma->kod_poczt   = $row['kod_poczt']; }
				if($row['miejscowosc']) { $Firma->miejscowosc = $row['miejscowosc']; }
				if($row['ul'])          { $Firma->ul          = $row['ul']; }
				if($row['nr_b'])        { $Firma->nr_b        = $row['nr_b']; }
				if($row['nr_l'])        { $Firma->nr_l        = $row['nr_l']; }
				if($row['tel'])         { $Firma->tel         = $row['tel']; }
				if($row['email'])       { $Firma->email       = $row['email']; }
				if($row['data_od'])     { $Firma->data_od     = $row['data_od']; }
				if($row['data_do'])     { $Firma->data_do     = $row['data_do']; }
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
				$row['nr_b'] = NULL;
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
   * @var \FirmyTable
   */
  protected $FirmyTable;

}