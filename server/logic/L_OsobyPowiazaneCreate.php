<?php
/**
 * @package crmsw
 * @subpackage logic
 * @author Piotr Janczura <piotr@janczura.pl>
 * @work 2014-10-20 Przebudowa Table do obsługi zapytań preparowanych
 * @confirm 2014-10-24 Duża przebudowa DependencyTableRecord
 * @todo \pjpl\a\BusinessLogic <<< \crmsw\lib\a\BusinessLogic z usunięciem dziesdziczenia po klasie ...\beta\BusinessLogic
 */
class L_OsobyPowiazaneCreate extends \crmsw\lib\a\BusinessLogic{
  public function __construct() {
    parent::__construct();
    $this->OsobyPowiazaneTable = $this->DB->tableOsobyPowiazane();
  }
  protected function logic() {
    foreach ($this->dataIn as $key => $row) {
      try{
        $eOsobaPowiazana = OsobaPowiazana::create($row);
				$rOsobaPowiazana = $this->OsobyPowiazaneTable->createRecordImmediately($eOsobaPowiazana);
				$this->dataOut[$key] = array('id' => $rOsobaPowiazana->id,'tmpId'=>  $row['tmpId'], 'rec' => $rOsobaPowiazana->toArray());
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
			if(isset($value['klient_id'])){
				$row['klient_id'] = $this->Firewall->string($value['klient_id']);
			}else{
				$row['klient_id'] = '';
			}
			if(isset($value['nazwa'])){
				$row['nazwa'] = $this->Firewall->string($value['nazwa']);
			}else{
				$row['nazwa'] = '';
			}
			if(isset($value['imie'])){
				$row['imie'] = $this->Firewall->string($value['imie']);
			}else{
				$row['imie'] = '';
			}
			if(isset($value['pesel'])){
				$row['pesel'] = $this->Firewall->serialNumber($value['pesel']);
			}else{
				$row['pesel'] = '';
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
			if(isset($value['email'])){
				$row['email'] = $this->Firewall->email($value['email']);
			}else{
				$row['email'] = '';
			}
			if(isset($value['telkom'])){
				$row['telkom'] = $this->Firewall->telefonNumber($value['telkom']);
			}else{
				$row['telkom'] = '';
			}
			if(isset($value['teldom'])){
				$row['teldom'] = $this->Firewall->telefonNumber($value['teldom']);
			}else{
				$row['teldom'] = '';
			}
			if(isset($value['telpraca'])){
				$row['telpraca'] = $this->Firewall->telefonNumber($value['telpraca']);
			}else{
				$row['telpraca'] = '';
			}
			$this->dataIn[$key] = $row;
		}
  }
  /**
   * @var OsobyPowiazaneTable
   */
  protected $OsobyPowiazaneTable;
}