<?php
/**
 * @package crmsw
 * @subpackage logic
 * @author Piotr Janczura <piotr@janczura.pl>
 * @work 2014-10-20 Przebudowa Table do obsługi zapytań preparowanych
 * @work 2014-10-20 Duża przebudowa DependencyTableRecord
 * @todo \pjpl\a\BusinessLogic <<< \crmsw\lib\a\BusinessLogic z usunięciem dziesdziczenia po klasie ...\beta\BusinessLogic
 */
class L_AdministratorzyCreate extends \crmsw\lib\a\BusinessLogic{
 public function __construct() {
    parent::__construct();
    $this->AdministratorzyTable = $this->DB->tableAdministratorzy();
  }
	protected function logic() {
		foreach ($this->dataIn as $key => $row) {
			try{
				$eAdministrator = new Administrator($row['login'], null, $row['email'], $row['tel']);
				$this->dataOut[$key] = array('id'=>$this->AdministratorzyTable->createRecordImmediately($eAdministrator)->getId(),'tmpId'=>  $row['tmpId']);
			} catch (Exception $ex) {
				$this->success = FALSE;
				$this->catchLogicException($ex);
			}
		}
	}
	public function fromRequest(&$_request) {
		$data = json_decode($_request);
		foreach ($data->data as $key => $value) {
			$row = array();
			if(isset($value->tmpId)){
				$row['tmpId'] = $this->Firewall->login($value->tmpId);
			}  else {
				$row['tmpId'] = null;
			}
			if(isset($value->login)){
				$row['login'] = $this->Firewall->login($value->login);
			}  else {
				$row['login'] = null;
			}
			if(isset($value->tel)){
				$row['tel'] = $this->Firewall->telefonNumber($value->tel);
			}else{
				$row['tel'] = null;
			}
			if(isset($value->email)){
				$row['email'] = $this->Firewall->email($value->email);
			}else{
				$row['email'] = null;
			}
			$this->dataIn[$key] = $row;
		}
	}
  /**
   * @var AdministratorzyTable
   */
  protected $AdministratorzyTable;
}