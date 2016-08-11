<?php
/**
 * @package crmsw
 * @subpackage logic
 * @author Piotr Janczura <piotr@janczura.pl>
 * @work 2014-10-20 Przebudowa Table do obsługi zapytań preparowanych
 * @work 2014-10-20 Duża przebudowa DependencyTableRecord
 * @todo \pjpl\a\BusinessLogic <<< \crmsw\lib\a\BusinessLogic z usunięciem dziesdziczenia po klasie ...\beta\BusinessLogic
 */
class L_AdministratorzyUpdate extends \crmsw\lib\a\BusinessLogic{
 public function __construct() {
    parent::__construct();
    $this->AdministratorzyTable = $this->DB->tableAdministratorzy();
  }
	protected function logic() {
		foreach ($this->dataIn as $key => $row) {
			try{
				$rAdministrator = $this->AdministratorzyTable->getRecord($row['id']);
				$rAdministrator->getEncja()->setLogin($row['login']);
				$rAdministrator->getEncja()->setTel($row['tel']);
				$rAdministrator->getEncja()->setEmail($row['email']);
				$this->AdministratorzyTable->updateRecordImmediately($rAdministrator);
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
		foreach ($we->data as $key => $data) {
			$row = array();
			if(isset($data->id)){
				$row['id'] = $this->Firewall->int($data->id);
			}  else {
				$row['id'] = null;
			}
			if(isset($data->login)){
				$row['login'] = $this->Firewall->login($data->login);
			}  else {
				$row['login'] = null;
			}
			if(isset($data->tel)){
				$row['tel'] = $this->Firewall->telefonNumber($data->tel);
			}else{
				$row['tel'] = null;
			}
			if(isset($data->email)){
				$row['email'] = $this->Firewall->email($data->email);
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