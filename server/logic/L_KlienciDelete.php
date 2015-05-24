<?php
/**
 * @package crmsw
 * @subpackage logic
 * @author Piotr Janczura <piotr@janczura.pl>
 * @confirm 2014-10-20 Przebudowa Table do obsługi zapytań preparowanych
 * @confirm 2014-10-20 Duża przebudowa DependencyTableRecord
 * @todo Kasowanie rekordu zablokowanego kluczem podrzędnym powinna ustawić data_do na null
 * @todo \pjpl\a\BusinessLogic <<< \crmsw\lib\a\BusinessLogic z usunięciem dziesdziczenia po klasie ...\beta\BusinessLogic
 */
class L_KlienciDelete extends \crmsw\lib\a\BusinessLogic{
  public function __construct() {
    parent::__construct();
    $this->KlienciTable = $this->DB->tableKlienci();
  }
	protected function logic() {
		foreach ($this->dataIn as $this->dataInKey => $row) {
			$this->dataOutKey = $this->dataInKey;
			try{
				$this->KlienciTable->deleteIdImmediately($row['id']);
				$this->dataOut[$this->dataOutKey] = array('id'=>$row['id'],'success'=>true);
			} catch (\Exception $E) {
				$this->catchLogicException($E);
			}
		}
	}
	public function fromRequest(&$_request) {
		$we = json_decode($_request);
		foreach ($we->data as $this->dataInKey => $data) {
			$row = array();
			if(isset($data->id)){
				$row['id'] = $this->Firewall->login($data->id);
			}  else {
				$row['id'] = null;
			}
			$this->dataIn[$this->dataInKey] = $row;
	  }
	}
	/**
   * @var KlienciTable
   */
  protected $KlienciTable;
}