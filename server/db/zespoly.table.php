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
class Zespol extends Encja{
  public function getLiderId(){
    return $this->lider_id;
  }
  public function getStanowiskoId(){
    return $this->stanowisko_id;
  }
  public function getDataOd(){
    return $this->data_od;
  }
  public function getDataDo(){
    return $this->data_do;
  }
  /**
   * @param int $lider_id
   * @throws EBadIn
   */
  public function setLiderId($lider_id){
    if(empty($lider_id)){
      $E = new EBadIn(__CLASS__, __FUNCTION__, '$lider_id', 'empty');
      throw $E;
    }
    $this->lider_id = $lider_id;
  }
  /**
   * @param int $stanowisko_id
   * @throws EBadIn
   */
  public function setStanowiskoId($stanowisko_id){
    if(empty($stanowisko_id)){
      $E = new EBadIn(__CLASS__, __FUNCTION__, '$stanowisko_id', 'empty');
      throw $E;
    }
    $this->stanowisko_id = $stanowisko_id;
  }
  public function setDataOd($data_od){
    $this->data_od  =$data_od;
  }
  public function setDataDo($data_do){
    $this->data_do = $data_do;
  }

	public static function nullRow() {
		return [
						'lider_id'      => NULL,
						'stanowisko_id' => NULL,
						'data_od'       => NULL,
						'data_do'       => NULL
		];
	}

}
class ZespolyDependence extends DependenceTableRecord{
  public function tabelaId() {
    return 13;
  }
  public function tableName() {
    return 'zespoly';
  }
	public function className() {
		return 'Zespol';
	}
}
class ZespolyTable extends Table{
  public static function getInstance() {
    if(!(self::$instance instanceof ZespolyTable)){
      self::$instance = new ZespolyTable(new ZespolyDependence(),DB::getInstance());
    }
    return self::$instance;
  }
}