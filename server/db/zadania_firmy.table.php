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
 * @work 2014-10-18 Duża przebudowa DependencyTableRecord
 * @work 2014-10-10 Przenieść kontrolę attrybutów do __set() i __get()
 */
class ZadanieFirmy extends Encja{
	public static function nullRow() {
		return [
						'nr_zadania' => NULL,
						'firma_id'   => NULL
		];
	}

	public function getNrZadania(){
    return $this->nr_zadania;
  }
  public function getFirmaId(){
    return $this->firma_id;
  }
  /**
   * @param int $nr_zadania
   * @throws EBadIn
   */
  public function setNrZadania($nr_zadania){
    if(empty($nr_zadania)){
      throw new EBadIn(__CLASS__,__METHOD__,'$zadanie','empty');
    }
    $this->nr_zadania = $nr_zadania;
  }
  /**
   * @param int $firma_id
   * @throws EBadIn
   */
  public function setFirmaId($firma_id){
    if(empty($firma_id)){
      throw new EBadIn(__CLASS__,__METHOD__,'$firma_id','empty');
    }
    $this->firma_id = $firma_id;
  }
}
class ZadaniaFirmyDependence extends DependenceTableRecord{
  public function tabelaId() {
    return 26;
  }
  public function tableName() {
    return 'zadania_firmy';
  }
	public function className() {
		return 'ZadanieFirmy';
	}
}
class ZadaniaFirmyTable extends Table{
  public static function getInstance() {
    if(!(self::$instance instanceof ZadaniaFirmyTable)){
      self::$instance = new ZadaniaFirmyTable(new ZadaniaFirmyDependence(),\crmsw\lib\db\DB::getInstance());
    }
    return self::$instance;
  }

}