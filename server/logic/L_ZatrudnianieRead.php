<?php
/**
 * @package crmsw
 * @subpackage logic
 * @author Piotr Janczura <piotr@janczura.pl>
 * @prace 2014-10-20 Przebudowa Table do obsługi zapytań preparowanych
 * @prace 2014-10-20 Duża przebudowa DependencyTableRecord
 * @todo \pjpl\a\BusinessLogic <<< \crmsw\lib\a\BusinessLogic z usunięciem dziesdziczenia po klasie ...\beta\BusinessLogic
 */

use pjpl\db\Where;

class L_ZatrudnianieRead extends \crmsw\lib\a\BusinessLogic{
  public function __construct() {
    parent::__construct();
    $this->ZatrudnieniaTable = $this->DB->tableZatrudnienia();
		$this->PracownicyTable = $this->DB->tablePracownicy();
  }
  protected function logic() {
		try{
			$this->PracownicyTable->where(new Where($this->dataIn['filter']))->limit($this->dataIn['start'], $this->dataIn['limit'])->load();
			for	($rPracownik = $this->PracownicyTable->getRecordFirst();	$rPracownik !== NULL;	$rPracownik = $this->PracownicyTable->getRecordNext()){

				$this->ZatrudnieniaTable->where(new Where([
						[
								'attribute' => 'pracownik_id',
								'operator'  => '=',
								'value'     => $rPracownik->getId()
						],[
								'attribute' => 'data_do',
								'operator'  => '=',
								'value'     => NULL
						]
				]))->load();

				if($this->ZatrudnieniaTable->count() > 0){
					continue;
				}else{
					$rec = array();
					$rec['id'] = -1 * $rPracownik->getId();
					$rec['firma_id'] = $rPracownik->getEncja()->getFirmaId();
					$rec['stanowisko_id'] = null;
					$rec['pracownik_id'] = $rPracownik->getId();
					$rec['nazwisko'] = $rPracownik->getEncja()->getNazwisko();
					$rec['imie'] = $rPracownik->getEncja()->getImie();
					$rec['pesel'] = $rPracownik->getEncja()->getPesel();
					$rec['data_od'] = NULL;
					$this->dataOut[] = $rec;
				}
				/**
				 * @todo Uwzględnić limity wczytywanych rekordów
				 */
			}
			$this->countOut = $this->PracownicyTable->count();
			$this->countFilteredOut = $this->PracownicyTable->countFiltered();
			$this->countTodalOut = $this->PracownicyTable->countTotal();
		} catch (Exception $ex) {
			$this->success = FALSE;
			$this->catchLogicException($ex);
		}
	}
  public function fromRequest(&$_request) {
		if(isset($_request['page'])){
			$this->dataIn['page'] = $this->Firewall->int($_request['page']);
		}  else {
			$this->dataIn['page'] = 0;
		}
		if(isset($_request['start'])){
			$this->dataIn['start'] = $this->Firewall->int($_request['start']);
		}  else {
			$this->dataIn['start'] = 0;
		}
		if(isset($_request['limit'])){
			$this->dataIn['limit'] = $this->Firewall->int($_request['limit']);
		}else{
			$this->dataIn['limit'] = 0;
		}
		if(isset($_request['filter'])){
			$this->dataIn['filter'] = $this->reformatExtJSFilter($_request['filter']);
		}  else {
			$this->dataIn['filter'] = [];
		}
  }

  /**
   * @var ZatrudnieniaTable
   */
  protected $ZatrudnieniaTable;
	/**
	 * @var PracownicyTable
	 */
	protected $PracownicyTable;
}