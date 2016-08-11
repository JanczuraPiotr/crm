<?php
/**
 * @package crmsw
 * @subpackage logic
 * @author Piotr Janczura <piotr@janczura.pl>
 * @work 2014-10-20 Przebudowa Table do obsługi zapytań preparowanych
 * @work 2014-10-20 Duża przebudowa DependencyTableRecord
 * @todo \pjpl\a\BusinessLogic <<< \crmsw\lib\a\BusinessLogic z usunięciem dziesdziczenia po klasie ...\beta\BusinessLogic
 */

use pjpl\db\Where;

class L_ZatrudnianieZwolnij extends \crmsw\lib\a\BusinessLogic{
  public function __construct() {
    parent::__construct();
    $this->ZatrudnieniaTable = $this->DB->tableZatrudnienia();
		$this->StanowiskaTable = $this->DB->tableStanowiska();
  }
  protected function logic() {
		try{
			$this->ZatrudnieniaTable->where(new Where([
					[
							'attribute' => 'stanowisko_id',
							'operator'  => '=',
							'value'     => $this->dataIn['stanowisko_id']
					],[
							'attribute' => 'data_do',
							'operator'  => '=',
							'value'     => NULL
					]
			]))->load();

			if($this->ZatrudnieniaTable->count()){
				$this->DB->beginTransaction();
				$rZatrudnienie = $this->ZatrudnieniaTable->getRecordFirst();
				$rZatrudnienie->getEncja()->setDataDo($this->dataIn['data_zwol']);
				$rZatrudnienie->updateImmediately();
				$rStanowisko = $this->StanowiskaTable->getRecordByPracownikId($rZatrudnienie->getEncja()->getPracownikId());
				$rStanowisko->getEncja()->setPracownikId(NULL);
				$rStanowisko->updateImmediately();
				$this->DB->commit();
			}else{
				// Stanowisko nie ma pracownika !!!
			}
			$this->dataOut = array('success'=>'true');
		}  catch (Exception $E){
			$this->DB->rollBack();
			$this->success = FALSE;
			$this->catchLogicException($E);
		}
  }

  public function fromRequest(&$_request) {
			if(isset($_request['stanowisko_id'])){
				$this->dataIn['stanowisko_id'] = $this->Firewall->int($_request['stanowisko_id']);
			}  else {
				$this->dataIn['stanowisko_id'] = null;
			}
        if(isset($_request['data_zwol'])){
          $this->dataIn['data_zwol'] = $this->Firewall->date($_request['data_zwol']);
        }  else {
          $this->dataIn['data_zwol'] = date('Y-m-d');
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