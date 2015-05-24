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
 * @prace 2014-10-10 Przenieść kontrolę attrybutów do __set() i __get()
 * @prace 2014-10-18 Duża przebudowa DependencyTableRecord
 */
class DokumentSlownik extends Encja{
	public static function nullRow() {
		return [
						'symbol' => NULL,
						'nazwa'  => NULL
		];
	}
  public function getSymbol(){
    return $this->symbol;
  }
  public function getNazwa(){
    return $this->nazwa;
  }
  /**
   * @param string $symbol
   * @throws EBadIn
   */
  public function setSymbol($symbol){
    if(empty($symbol)){
      throw new EBadIn(__CLASS__,__METHOD__,'$symbol','empty');
    }
    $this->symbol = $symbol;
  }
  public function setNazwa($nazwa){
    $this->nazwa = $nazwa;
  }
}
class DokumentySlownikDependence extends DependenceTableRecord{
  public function tabelaId() {
    return 24;
  }
  public function tableName() {
    return 'dokumenty_slownik';
  }
	public function className() {
		return 'DokumentSlownik';
	}
}

class DokumentySlownikTable extends Table{
  public static function getInstance() {
    if(!(self::$instance instanceof DokumentySlownikTable)){
      self::$instance = new DokumentySlownikTable(new DokumentySlownikDependence(),DB::getInstance());
    }
    return self::$instance;
  }

}