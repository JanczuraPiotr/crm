<?php
/**
 * @package crmsw
 * @subpackage database
 * @author Piotr Janczura <piotr@janczura.pl>
 * @work 2014-10-20 Przebudowa Table do obsługi zapytań preparowanych
 * @work 2014-10-20 Duża przebudowa DependencyTableRecord
 * @todo \pjpl\a\BusinessLogic <<< \crmsw\lib\a\BusinessLogic z usunięciem dziesdziczenia po klasie ...\beta\BusinessLogic
 */
class L_DokumentyProduktuUpdate extends \crmsw\lib\a\BusinessLogic{
	public function __construct() {
		parent::__construct();
		$this->DokumentyProduktuTable = $this->DB->tableDokumentyProduktu();
	}
	protected function logic() {
		foreach ($this->dataIn as $key => $row) {
			try{
				$this->DokumentyProduktuTable->setFiltrKeyValueAndAndRead(array('produkt_id'=>  $row['produkt_id'],'slownik_id'=>$row['slownik_id']));
				if($this->DokumentyProduktuTable->count() == 0 && $row['wymagany'] == true){
					$DokumentProduktu = new DokumentProduktu($row['produkt_id'], $row['slownik_id']);
					$this->dataOut[$key] = array('id'=>$this->DokumentyProduktuTable->createRecordImmediately($DokumentProduktu)->getId(),'tmpId'=>$row['id']);
				}else if($row['wymagany'] === false){
					$rDokumentProduktu = $this->DokumentyProduktuTable->getRecordFirst();
					do{
						$tmpId = $rDokumentProduktu->getId();
						$this->DokumentyProduktuTable->deleteIdImmediately($rDokumentProduktu->getId());
						$this->dataOut[$key] = array('id'=>  $tmpId, 'success'=>true);
					}while($rDokumentProduktu = $this->DokumentyProduktuTable->getRecordNext());
				}
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
			if(isset($value['wymagany'])){
				$row['wymagany'] = $this->Firewall->boolean($value['wymagany']);
			}else{
				$row['wymagany'] = false;
			}
			$this->dataIn[$key] = $row;
		}
	}
	/**
	 * @var DokumentyProduktuTable
	 */
	protected $DokumentyProduktuTable;
}