<?php
/**
 * @package crmsw
 * @subpackage database
 * @author Piotr Janczura <piotr@janczura.pl>
 * @work 2014-10-20 Przebudowa Table do obsługi zapytań preparowanych
 * @work 2014-10-20 Duża przebudowa DependencyTableRecord
 * @todo \pjpl\a\BusinessLogic <<< \crmsw\lib\a\BusinessLogic z usunięciem dziesdziczenia po klasie ...\beta\BusinessLogic
 */
/**
 * @todo Klasa prawdopodobnie nie jest używana
 */
class L_DokumentyProduktuCreate extends \crmsw\lib\a\BusinessLogic{
	public function __construct() {
		parent::__construct();
		$this->DokumentyProduktuTable = $this->DB->tableDokumentyProduktu();
	}
	protected function logic() {
		foreach ($this->dataIn as $key => $row) {
			try {
				$DokumentProduktu = new DokumentProduktu($row['produkt_id'], $row['slownik_id']);
				$this->dataOut[$key] = array('id'=>  $this->DokumentyProduktuTable->createRecordImmediately($DokumentProduktu)->getId(),'tmpId'=>$row['tmpId']);
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
			if(isset($value['tmpId'])){
				$row['tmpId'] = $this->Firewall->int($value['tmpId']);
			}  else {
				$row['tmpId'] = null;
			}
			if(isset($value['produkt_id'])){
				$row['produkt_id'] = $this->Firewall->int($value['produkt_id']);
			}else{
				$row['produkt_id'] = null;
			}
			if(isset($value['slownik_id'])){
				$row['slownik_id'] = $this->Firewall->int($value['slownik_id']);
			}else{
				$row['slownik_id'] = null;
			}
			$this->dataIn[$key] = $row;
		}
	}
	/**
	 * @var DokumentyProduktuTable
	 */
	protected $DokumentyProduktuTable;
}