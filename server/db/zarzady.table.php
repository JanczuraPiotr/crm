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
 * @task 2014-10-18 Duża przebudowa DependencyTableRecord
 * @task 2014-10-10 Przenieść kontrolę attrybutów do __set() i __get()
 */
class Zarzad extends Encja{

	public static function nullRow() {
		return [
						'status'       => NULL,
						'pracownik_id' => NULL,
						'firma_id'     => NULL,
						'data_od'      => NULL,
						'data_do'      => NULL
		];
	}

  /**
   * @param int $pracownik_id
   * @throws EBadIn
   */
  public function setPracownikId($pracownik_id){
    if(empty($pracownik_id)){
      throw new EBadIn(__CLASS__,__METHOD__,'$pracownik_id','empty');
    }
    $this->pracownik_id = $pracownik_id;
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
  public function setDataDo($data_do = null){
    $this->data_do = $data_do;
  }
  public function setDataOd($data_od){
    $this->data_od = $data_od;
  }
	public function setStatus($status){
		$this->status = $status;
	}
	public function getPracownikId(){
    return $this->pracownik_id;
  }
  public function getFirmaId(){
    return $this->firma_id;
  }
  public function getLogin(){
    return $this->login;
  }
  public function getHaslo(){
    return $this->haslo;
  }
  public function getDataOd(){
    return $this->data_od;
  }
  public function getDataDo(){
    return $this->data_do;
  }
	public function getStatus(){
		return $this->status;
	}

}
class ZarzadyDependence extends DependenceTableRecord{
  public function tabelaId() {
    return 8;
  }
  public function tableName() {
    return 'zarzady';
  }
	public function className() {
		return 'Zarzad';
	}

}
class ZarzadyTable extends Table{
  public static function getInstance() {
    if(!(self::$instance instanceof ZarzadyTable)){
      self::$instance = new ZarzadyTable(new ZarzadyDependence(),\crmsw\lib\db\DB::getInstance());
    }
    return self::$instance;
  }

}