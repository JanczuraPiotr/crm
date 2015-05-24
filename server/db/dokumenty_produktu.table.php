<?php
// @todo namespace crmsw\db;
// @todo use \pjpl\db\Encja;
use crmsw\lib\db\a\DependenceTableRecord;
use crmsw\lib\db\a\Table;
use crmsw\lib\db\DB;
use crmsw\lib\db\a\Encja;
use crmsw\lib\db\Record;
/**
 * @package crmsw
 * @subpackage database
 * @author Piotr Janczura <piotr@janczura.pl>
 * @prace 2014-10-18 Duża przebudowa DependencyTableRecord
 * @prace 2014-10-10 Przenieść kontrolę attrybutów do __set() i __get()
 */
class DokumentProduktu extends Encja{
	public static function nullRow() {
		return [
						'produkt_id' => NULL,
						'slownik_id' => NULL
		];
	}
  public function getProduktId(){
    return $this->produkt_id;
  }
  public function getSlownikId(){
    return $this->slownik_id;
  }
  /**
   * @param int $produkt_id
   * @throws EBadIn
   */
  public function setProduktId($produkt_id){
    if(empty($produkt_id)){
      throw new EBadIn(__CLASS__,__FUNCTION__,'$produkt_id','empty');
    }
    $this->produkt_id = $produkt_id;
  }
  /**
   * @param int $slownik_id
   * @throws EBadIn
   */
  public function setSlownikId($slownik_id){
    if(empty($slownik_id)){
      throw new EBadIn(__CLASS__,__FUNCTION__,'$slownik_id','empty');
    }
    $this->slownik_id = $slownik_id;
  }
}
class DokumentyProduktuDependence extends DependenceTableRecord{
  public function tabelaId() {
    return 22;
  }
  public function tableName() {
    return 'dokumenty_produktu';
  }
	public function className() {
		return 'DokumentProduktu';
	}
}
class DokumentyProduktuTable extends Table{
  public static function getInstance() {
    if(!(self::$instance instanceof DokumentyProduktuTable)){
      self::$instance = new DokumentyProduktuTable(new DokumentyProduktuDependence(),DB::getInstance());
    }
    return self::$instance;
  }

}