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
 * @done 2014-11-05 Duża przebudowa DependencyTableRecord
 * @task 2014-10-10 Przenieść kontrolę attrybutów do __set() i __get()
 */
class ZadanieDokument extends Encja{
	public function __get($attribute) {
		switch($attribute){
			case 'id':
			case 'slownik_id':
			case 'nr_zadania':
				return (int)$this->attributes[$attribute];
			default :
				return parent::__get($attribute);
		}
	}

	public static function nullRow() {
		return [
						'slownik_id' => NULL,
						'nr_zadania' => NULL,
						'adnotacje'  => NULL,
						'data_dostarczenia' => NULL
		];
	}

//	public function getAdnotacje(){
//		return $this->adnotacje;
//	}
//	public function getDataDostarczenia(){
//		return $this->data_dostarczenia;
//	}
//	/**
//   * @param int $slownik_id
//   * @throws EBadIn
//   */
//  public function setSlownikId($slownik_id){
//    if(empty($slownik_id)){
//      throw new EBadIn(__CLASS__,__METHOD__,'$slownik_id','empty');
//    }
//    $this->slownik_id = $slownik_id;
//  }
//  /**
//   * @param int $nr_zadania
//   * @throws EBadIn
//   */
//  public function setNrZadania($nr_zadania){
//    if(empty($nr_zadania)){
//      throw new EBadIn(__CLASS__,__METHOD__,'$zadanie', 'empty');
//    }
//    $this->nr_zadania = $nr_zadania;
//  }
//  public function setAdnotacje($adnotacje){
//    $this->adnotacje = $adnotacje;
//  }
//  public function setDataDostarczenia($data_dostarczenia){
//    $this->data_dostarczenia = $data_dostarczenia;
//  }
}
class ZadaniaDokumentyDependence extends DependenceTableRecord{
  public function tabelaId() {
    return 23;
  }
  public function tableName() {
    return 'zadania_dokumenty';
  }
	public function className() {
		return 'ZadanieDokument';
	}
}
class ZadaniaDokumentyTable extends Table{
  public static function getInstance() {
    if(!(self::$instance instanceof ZadaniaDokumentyTable)){
      self::$instance = new ZadaniaDokumentyTable(new ZadaniaDokumentyDependence(),DB::getInstance());
    }
    return self::$instance;
  }

}