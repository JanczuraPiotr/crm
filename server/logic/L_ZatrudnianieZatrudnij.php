<?php
/**
 * @package crmsw
 * @subpackage logic
 * @author Piotr Janczura <piotr@janczura.pl>
 * @prace 2014-10-20 Przebudowa Table do obsługi zapytań preparowanych
 * @prace 2014-10-20 Duża przebudowa DependencyTableRecord
 * @todo \pjpl\a\BusinessLogic <<< \crmsw\lib\a\BusinessLogic z usunięciem dziesdziczenia po klasie ...\beta\BusinessLogic
 */
class L_ZatrudnianieZatrudnij extends \crmsw\lib\a\BusinessLogic{
  public function __construct() {
    parent::__construct();
    $this->ZatrudnieniaTable = $this->DB->tableZatrudnienia();
		$this->StanowiskaTable = $this->DB->tableStanowiska();
  }

  protected function logic() {
    foreach ($this->dataIn as $key => $row) {
			/**
			 * @todo Przefiltrować pod względem zatrudnienia na kilku stanowiskach w jednum czasie
			 */
      try{
				$this->DB->beginTransaction();
        $Zatrudnienie = \Zatrudnienie::create($row);
				$this->dataOut[$key] = array('id'=>$this->ZatrudnieniaTable->createRecordImmediately($Zatrudnienie)->getId(),'tmpId'=> $row['tmpId']);
				$rStanowsko = $this->StanowiskaTable->getRecord($row['stanowisko_id']);
				$rStanowsko->getEncja()->setPracownikId($row['pracownik_id']);
				$rStanowsko->updateImmediately();
				$this->DB->commit();
      }  catch (Exception $E) {
				$this->DB->rollBack();
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
			if(isset($value['stanowisko_id'])){
				$row['stanowisko_id'] = $this->Firewall->int($value['stanowisko_id']);
			}else{
				$row['stanowisko_id'] = null;
			}
			if(isset($value['pracownik_id'])){
				$row['pracownik_id'] = $this->Firewall->int($value['pracownik_id']);
			}else{
				$row['pracownik_id'] = null;
			}
			if(isset($value['data_od'])){
				$row['data_od'] = $this->Firewall->date($value['data_od']);
			}else{
				$row['data_od'] = null;//$row['data_od'] = date('Y=m-d');
			}
			$this->dataIn[$key] = $row;
		}
  }
  /**
   * @var ZatrudnieniaTable
   */
  protected $ZatrudnieniaTable;
	/**
	 * @var StanowiskaTable
	 */
	protected $StanowiskaTable;
}