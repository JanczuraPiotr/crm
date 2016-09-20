<?php
namespace crmsw\logic\tasks;
use crmsw\lib\a\BusinessLogic;
/**
 * Dodaje adnotacje do zadania. W interfejsie urzytkownika adnotacje te nazywane są też "aktami sprawy".
 * @package crmsw
 * @subpackage logic
 * @author Piotr Janczura <piotr@janczura.pl>
 * @done 2014-12-16
 */
class CreateDescription extends BusinessLogic{
 public function __construct() {
    parent::__construct();
    $this->ZadaniaOpisTable = $this->DB->tableZadaniaOpis();
  }
	protected function logic() {
		foreach ($this->dataIn as $key => $row) {
			try{
				$eZadanieOpis = \ZadanieOpis::create($row);
				$rZadanieOpis = $this->ZadaniaOpisTable->createRecordImmediately($eZadanieOpis);
				$this->dataOut[$key] = array('id'=>$rZadanieOpis->getId(),'tmpId'=>  $row['tmpId'], 'create'=>$rZadanieOpis->getCreate());
			} catch (\Exception $ex) {
				$this->success = FALSE;
				$this->catchException($ex);
			}
		}
	}
	public function fromRequest(&$_request) {
		$data = json_decode($_request);
		foreach ($data->data as $key => $value) {
			$row = array();
			if(isset($value->tmpId)){
				$row['tmpId'] = $this->Firewall->int($value->tmpId);
			}  else {
				$row['tmpId'] = null;
			}
			if(isset($value->nr_zadania)){
				$row['nr_zadania'] = $this->Firewall->int($value->nr_zadania);
			}  else {
				$row['nr_zadania'] = null;
			}
			if(isset($value->notatka)){
				$row['notatka'] = $this->Firewall->string($value->notatka);
			}else{
				$row['notatka'] = null;
			}
			$this->dataIn[$key] = $row;
		}
	}

	/**
   * @var \ZadaniaOpisyTable
   */
  protected $ZadaniaOpisTable;
}