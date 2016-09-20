<?php
/**
 * @package crmsw
 * @subpackage logic
 * @author Piotr Janczura <piotr@janczura.pl>
 * @task 2014-10-20 Przebudowa Table do obsługi zapytań preparowanych
 * @task 2014-10-20 Duża przebudowa DependencyTableRecord
 * @todo Kasowanie rekordu zablokowanego kluczem podrzędnym powinna ustawić data_do na null
 * @todo \pjpl\a\BusinessLogic <<< \crmsw\lib\a\BusinessLogic z usunięciem dziesdziczenia po klasie ...\beta\BusinessLogic
 */

class L_WiazanieFirmBankowDelete extends \crmsw\lib\a\BusinessLogic{
	public function __construct() {
		parent::__construct();
		$this->BankiOddzialyFirmyOddzialyTable = $this->DB->tableBankiOddzialyFirmyOddzialy();
	}
	protected function logic() {
		foreach ($this->dataIn as $key => $row) {
			try{
				$rPowiazanie = $this->BankiOddzialyFirmyOddzialyTable->getRecord($row['id']);
				$rPowiazanie->getEncja()->setDataDo($row['data_do']);
				$rPowiazanie->updateImmediately();
        $this->dataOut[$key] = array('success'=>true,'id'=>$row['id']);
      }catch (\Exception $E){
        $this->success = FALSE;
        $this->catchLogicException($E);
      }
		}
	}
	public function fromRequest(&$_request) {
		$we = json_decode($_request);
//		print_r($we);
		foreach ($we->data as $key => $data) {
			$row = array();
			if(isset($data->id)){
				$row['id'] = $this->Firewall->login($data->id);
			}  else {
				$row['id'] = null;
			}
			if(isset($value['data_do'])){
				$row['data_do'] = $this->Firewall->date($value['data_do']);
			}else{
				$row['data_do'] = date('Y-m-d');
			}
			$this->dataIn[$key] = $row;
		}
	}
	/**
	 * @var BankiOddzialyFirmyOddzialyTable
	 */
	protected $BankiOddzialyFirmyOddzialyTable;
}