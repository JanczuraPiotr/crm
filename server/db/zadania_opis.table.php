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
 * @done 2014-12-16
 * @task 2014-10-10 Przenieść kontrolę attrybutów do __set() i __get()
 */
class ZadanieOpis extends Encja{
	public static function nullRow() {
		return [
						'nr_zadania' => NULL,
						'notatka'    => NULL
		];
	}
//
//
//	public function getNrZadania(){
//    return $this->nr_zadania;
//  }
//  public function getNotatka(){
//    return $this->notatka;
//  }
//  /**
//   * @param int $nr_zadania
//   * @throws EBadIn
//   */
//  public function setNrZadania($nr_zadania){
//    if(empty($nr_zadania)){
//      throw new EBadIn(__CLASS__,__METHOD__,'$zdanie','empty');
//    }
//    $this->nr_zadania = $nr_zadania;
//  }
//  /**
//   * @param int $notatka
//   * @throws EBadIn
//   */
//  public function setNotatka($notatka){
//    if(empty($notatka)){
//      throw new EBadIn(__CLASS__,__METHOD__,'$notatka','empty');
//    }
//    $this->notatka = $notatka;
//  }
//
}
class ZadaniaOpisyDependence extends DependenceTableRecord{
  public function tabelaId() {
    return 27;
  }
  public function tableName() {
    return 'zadania_opis';
  }
	public function className() {
		return 'ZadanieOpis';
	}
}
class ZadaniaOpisyTable extends Table{
  public static function getInstance() {
    if( ! (self::$instance instanceof ZadaniaOpisyTable)){
      self::$instance = new ZadaniaOpisyTable(new ZadaniaOpisyDependence(),DB::getInstance());
    }
    return self::$instance;
  }
}