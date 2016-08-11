<?php
/**
 * @package crmsw
 * @subpackage logic
 * @author Piotr Janczura <piotr@janczura.pl>
 * @work 2014-10-20 Przebudowa Table do obsługi zapytań preparowanych
 * @work 2014-10-20 Duża przebudowa DependencyTableRecord
 * @todo \pjpl\a\BusinessLogic <<< \crmsw\lib\a\BusinessLogic z usunięciem dziesdziczenia po klasie ...\beta\BusinessLogic
 */
class L_PrezesZarzaduRead extends \crmsw\lib\a\BusinessLogic{
  public function __construct() {
    parent::__construct();
    $this->ZarzadyTable = $this->DB->tableZarzady();
  }

	protected function logic() {
		$this->ZarzadyTable->setFiltrKeyValueAndAndRead(array('firma_id'=>  $this->dataIn['firma_id'],'status'=>CRM::ZARZAD_PREZES,'data_do'=>NULL));
		$rPrezes = $this->ZarzadyTable->getRecordFirst();
		if($rPrezes !== null){
			$this->dataOut['id'] = $rPrezes->getId();
		}else{
			$this->dataOut['id'] = null;
		}
	}

	public function fromRequest(&$_request) {
		if(isset($_request['firma_id'])){
			$this->dataIn['firma_id'] = $_request['firma_id'];
		}else{
			$this->dataIn['firma_id'] = null;
		}
	}
	/**
   * @var ZarzadyTable
   */
  protected $ZarzadyTable;
}