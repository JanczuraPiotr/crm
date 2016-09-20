<?php
/**
 * @package crmsw
 * @subpackage logic
 * @author Piotr Janczura <piotr@janczura.pl>
 * @task 2014-10-20 Przebudowa Table do obsługi zapytań preparowanych
 * @task 2014-10-20 Duża przebudowa DependencyTableRecord
 * @todo \pjpl\a\BusinessLogic <<< \crmsw\lib\a\BusinessLogic z usunięciem dziesdziczenia po klasie ...\beta\BusinessLogic
 */
class L_LiderzyUpdate extends \crmsw\lib\a\BusinessLogic{
	public function __construct() {
		parent::__construct();
		$this->tLiderzy = $this->DB->tableLiderzy();
	}

	protected function logic() {
		foreach ($this->dataIn as $key => $value) {
			try{
				$this->DB->beginTransaction();
				$rLider = $this->tLiderzy->getRecord($value['id']);
				if($rLider->getEncja()->getDataDo() !== $value['data_do']){
					$zmiana = 'zmiana';
				}
				$eLider = $rLider->getEncja();
				$eLider->setSymbol($value['symbol']);
				$eLider->setNazwa($value['nazwa']);
				$eLider->setOpis($value['opis']);
				$eLider->setDataOd($value['data_od']);
				$eLider->setDataDo($value['data_do']);
				$rLider->updateImmediately();
				if(isset($zmiana)){
					// Ustawiono datę zamknięcia zespołu.
					// Należy tą samą datę ustawić dla wszystkich członków tego zespołu
					$this->tZespoly = $this->DB->tableZespoly();
					$this->tZespoly->setFiltrKeyValueAndAndRead(array('lider_id'=>$value['id']));
					for($rZespol = $this->tZespoly->getRecordFirst(); $rZespol !== NULL;$rZespol = $this->tZespoly->getRecordNext()){
						$rZespol->getEncja()->setDataDo($eLider->getDataDo());
						$rZespol->updateImmediately();
					}
				}
				$this->DB->commit();
				$this->dataOut[$key] = array('success'=>true,'id'=>$value['id']);
      }  catch (Exception $E){
				$this->DB->rollBack();
				$this->success = FALSE;
        $this->catchLogicException($E);
      }
    }
	}
	private function update($key,$row){
	}
	public function fromRequest(&$_request) {
		$we = json_decode($_request, TRUE);
		foreach ($we['data'] as $key => $value) {
			$row =array();
			if(isset($value['id'])){
				$row['id'] = $this->Firewall->date($value['id']);
			}  else {
				$row['id'] = date('Y-m-d');
			}
			if(isset($value['stanowisko_id'])){
				$row['stanowisko_id'] = $this->Firewall->date($value['stanowisko_id']);
			}  else {
				$row['stanowisko_id'] = date('Y-m-d');
			}
			if(isset($value['symbol'])){
				$row['symbol'] = $this->Firewall->string($value['symbol']);
			}  else {
				$row['symbol'] = null;
			}
			if(isset($value['nazwa'])){
				$row['nazwa'] = $this->Firewall->string($value['nazwa']);
			}  else {
				$row['nazwa'] = null;
			}
			if(isset($value['opis'])){
				$row['opis'] = $this->Firewall->string($value['opis']);
			}  else {
				$row['opis'] = null;
			}
			if(isset($value['data_od'])){
				$row['data_od'] = $this->Firewall->date($value['data_od']);
			}  else {
				$row['data_od'] = date('Y-m-d');
			}
			if(isset($value['data_do'])){
				$row['data_do'] = $this->Firewall->date($value['data_do']);
			}  else {
				$row['data_do'] = NULL;
			}
			$this->dataIn[$key] = $row;
		}
	}
	/**
	 * @var LiderzyTable
	 */
	protected $tLiderzy;
	/**
	 * @var ZespolyTable
	 */
	protected $tZespoly;
}