<?php
/**
 * @package crmsw
 * @subpackage database
 * @author Piotr Janczura <piotr@janczura.pl>
 * @work 2014-10-20 Przebudowa Table do obsługi zapytań preparowanych
 * @work 2014-10-20 Duża przebudowa DependencyTableRecord
 * @todo \pjpl\a\BusinessLogic <<< \crmsw\lib\a\BusinessLogic z usunięciem dziesdziczenia po klasie ...\beta\BusinessLogic
 */
class L_DokumentyProduktuRead extends \crmsw\lib\a\BusinessLogic{
	public function __construct() {
		parent::__construct();
		$this->DokumentyProduktuTable = $this->DB->tableDokumentyProduktu();
		$this->DokumentySlownikTable = $this->DB->tableDokumentySlownik();
	}
	protected function logic() {
		try{
			$produkt_id = $this->dataIn['filter'][0]['value'];
			$this->DokumentySlownikTable->loadStartLimit($this->dataIn['start'], $this->dataIn['limit']);
			$rSlownik = $this->DokumentySlownikTable->getRecordFirst();
			do{
				$aSlownik = $this->DokumentySlownikTable->getDI()->fromRecordToArray($rSlownik);
				$this->DokumentyProduktuTable->setFiltrKeyValueAndAndRead(array('slownik_id'=>$rSlownik->getId(),'produkt_id'=>$produkt_id));
				$rProdukt = $this->DokumentyProduktuTable->getRecordFirst();
				if($rProdukt !== NULL){
					$wymagany = TRUE;
					$id = $rSlownik->getId();
				}else{
					$wymagany = FALSE;
					$id = '-'.$aSlownik['id'].$produkt_id ;
				}
				$this->dataOut[] = array(
						'id' => $id,
						'symbol' => $aSlownik['symbol'],
						'nazwa' => $aSlownik['nazwa'],
						'slownik_id'=>$aSlownik['id'],
						'produkt_id'=>$produkt_id,
						'wymagany' => $wymagany
				);
			}while($rSlownik = $this->DokumentySlownikTable->getRecordNext());
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
			$this->dataIn['filter'] = json_decode($_request['filter'],TRUE);
		}  else {
			$this->dataIn['filter'] = null;
		}
	}
	/**
	 * @var DokumentyProduktuTable
	 */
	protected $DokumentyProduktuTable;
	/**
	 * @var DokumentySlownikTable
	 */
	protected $DokumentySlownikTable;
}