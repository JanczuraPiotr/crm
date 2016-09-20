<?php
/**
 * @package crmsw
 * @subpackage logic
 * @author Piotr Janczura <piotr@janczura.pl>
 * @done 2014-12-29
 * @todo Kasowanie rekordu zablokowanego kluczem podrzędnym powinna ustawić data_do na null
 * @todo \pjpl\a\BusinessLogic <<< \crmsw\lib\a\BusinessLogic z usunięciem dziesdziczenia po klasie ...\beta\BusinessLogic
 */

class L_FirmyDelete extends \crmsw\lib\a\BusinessLogic{
  public function __construct() {
    parent::__construct();
    $this->FirmyTable = $this->DB->tableFirmy();
  }
	protected function logic() {
		foreach ($this->dataIn as $key => $row) {
			try{
				$this->FirmyTable->deleteIdImmediately($row['id']);
				$this->dataOut[$key] = array('id'=>$row['id'],'success'=>true);
			} catch (Exception $ex) {
				$this->dataOut[$key] = array('id'=>$row['id'],'success'=>false);
				$this->success = FALSE;
				$this->catchLogicException($ex);
			}
		}
	}
	public function fromRequest(&$_request) {
		$we = json_decode($_request);
		foreach ( $we->data as $key => $data ) {
			$row = array();
			if(isset($data->id)){
				$row['id'] = $this->Firewall->login($data->id);
			}  else {
				$row['id'] = null;
			}
			$this->dataIn[$key] = $row;
		}
	}
  /**
   * @var \FirmyTable
   */
  protected $FirmyTable;
}