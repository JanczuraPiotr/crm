<?php
/**
 * @package crmsw
 * @subpackage database
 * @author Piotr Janczura <piotr@janczura.pl>
 * @work 2014-10-20 Przebudowa Table do obsługi zapytań preparowanych
 * @work 2014-10-20 Duża przebudowa DependencyTableRecord
 * @todo \pjpl\a\BusinessLogic <<< \crmsw\lib\a\BusinessLogic z usunięciem dziesdziczenia po klasie ...\beta\BusinessLogic
 */

class L_ProduktyCreate extends \crmsw\lib\a\BusinessLogic{
	public function __construct() {
		parent::__construct();
		$this->ProduktyTable = $this->DB->tableProdukty();
	}
	protected function logic() {
    foreach ($this->dataIn as $key => $row) {
      try{
        $Produkt = new Produkt(
								$row['bank_id'],
								$row['symbol'],
								$row['nazwa'],
								$row['opis'],
								$row['data_od'],
								null);
        $this->dataOut[$key] = array('id'=>$this->ProduktyTable->createRecordImmediately($Produkt)->getId(),'tmpId'=>  $row['tmpId']);
      }  catch (\Exception $E){
				$this->success = FALSE;
        $this->catchLogicException($E);
      }
    }
	}

	public function fromRequest(&$_request) {
		$we = json_decode($_request,TRUE);
		foreach ($we['data'] as $key => $value) {
			$row = array();
			if(isset($value['tmpId'])){
				$row['tmpId'] = $this->Firewall->int($value['tmpId']);
			}  else {
				$row['tmpId'] = null;
			}
			if(isset($value['bank_id'])){
				$row['bank_id'] = $this->Firewall->int($value['bank_id']);
			}  else {
				$row['bank_id'] = null;
			}
			if(isset($value['symbol'])){
				$row['symbol'] = $this->Firewall->word($value['symbol']);
			}else{
				$row['symbol'] = null;
			}
			if(isset($value['nazwa'])){
				$row['nazwa'] = $this->Firewall->string($value['nazwa']);
			}else{
				$row['nazwa'] = null;
			}
			if(isset($value['opis'])){
				$row['opis'] = $this->Firewall->string($value['opis']);
			}else{
				$row['opis'] = null;
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
	 * @var ProduktyTable
	 */
	protected $ProduktyTable;
}