<?php
/**
 * @package crmsw
 * @subpackage logic
 * @author Piotr Janczura <piotr@janczura.pl>
 * @done 2014-12-29
 * @todo \pjpl\a\BusinessLogic <<< \crmsw\lib\a\BusinessLogic z usunięciem dziesdziczenia po klasie ...\beta\BusinessLogic
 */

class L_FirmyOddzialyCreate extends \crmsw\lib\a\BusinessLogic{
  public function __construct() {
    parent::__construct();
    $this->FirmyOddzialyTable = $this->DB->tableFirmyOddzialy();
  }
  protected function logic() {
    foreach ($this->dataIn as $key => $row) {
      try{
        $FirmaOddzial = \FirmaOddzial::create([
						'firma_id'    => $row['firma_id'],
						'symbol'      => $row['symbol'],
						'nazwa'       => $row['nazwa'],
						'nip'         => $row['nip'],
						'kod_poczt'   => $row['kod_poczt'],
						'miejscowosc' => $row['miejscowosc'],
						'ul'          => $row['ul'],
						'nr_b'        => $row['nr_b'],
						'nr_l'        => $row['nr_l'],
						'tel'         => $row['tel'],
						'email'       => $row['email'],
						'data_od'     => $row['data_od'],
						'data_do'     => null
				]);
        $this->dataOut[$key] = array('id'=>$this->FirmyOddzialyTable->createRecordImmediately($FirmaOddzial)->getId(),'tmpId'=>  $row['tmpId']);
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
				$row['tmpId'] = NULL;
			}
			if(isset($value['firma_id'])){
				$row['firma_id'] = $this->Firewall->word($value['firma_id']);
			}else{
				$row['firma_id'] = NULL;
			}
			if(isset($value['symbol'])){
				$row['symbol'] = $this->Firewall->word($value['symbol']);
			}else{
				$row['symbol'] = NULL;
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
				$row['data_od'] = date('Y-m-d');
			}
			$this->dataIn[$key] = $row;
    }
  }
  /**
   * @var \FirmyOddzialyTable
   */
  protected $FirmyOddzialyTable;
}