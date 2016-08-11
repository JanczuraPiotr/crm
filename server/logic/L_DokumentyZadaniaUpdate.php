<?php
/**
 * @package crmsw
 * @subpackage database
 * @author Piotr Janczura <piotr@janczura.pl>
  * @work 2014-10-20 Przebudowa Table do obsługi zapytań preparowanych
 * @work 2014-10-20 Duża przebudowa DependencyTableRecord
* @todo \pjpl\a\BusinessLogic <<< \crmsw\lib\a\BusinessLogic z usunięciem dziesdziczenia po klasie ...\beta\BusinessLogic
 */
class L_DokumentyZadaniaUpdate extends \crmsw\lib\a\BusinessLogic{
	public function __construct() {
		parent::__construct();
		$this->DokumentyZadaniaTable = $this->DB->tableDokumentyZadania();
	}
	protected function logic() {
		foreach ($this->dataIn as $key => $row) {
			try{
				$rDokumentyZadania = $this->DokumentyZadaniaTable->getRecord($row['id']);
				$rDokumentyZadania->getEncja()->setDataDostarczenia($row['data_dostarczenia']);
				$rDokumentyZadania->updateImmediately();
				$this->dataOut[$key] = array('id'=>$row['id'],'success'=>true);
			} catch (Exception $E) {
				$this->success = FALSE;
				$this->catchLogicException($E);
			}
		}
	}
	public function fromRequest(&$_request) {
		$we = json_decode($_request,TRUE);
		foreach ($we['data'] as $key => $value) {
			$row = array();
			if(isset($value['id'])){
				$row['id'] = $this->Firewall->int($value['id']);
			}  else {
				$row['id'] = null;
			}
			if(isset($value['data_dostarczenia'])){
				$row['data_dostarczenia'] = $this->Firewall->date($value['data_dostarczenia']);
			}else{
				$row['data_dostarczenia'] = null;
			}
			$this->dataIn[$key] = $row;
		}
	}
	/**
	 * @var ZadaniaDokumentyTable
	 */
	protected $DokumentyZadaniaTable;
}