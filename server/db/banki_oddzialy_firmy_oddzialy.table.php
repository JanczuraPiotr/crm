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
 * @work 2014-10-10 Przenieść kontrolę attrybutów do __set() i __get()
 * @work 2014-10-18 Duża przebudowa DependencyTableRecord
 */
class BankOddzialFirmaOddzial extends Encja{
  /**
   *
   * @param int $firma_oddzial_id
   * @throws EBadIn
   */
  public function setFirmaOddzialId($firma_oddzial_id){
    if(empty($firma_oddzial_id)){
      $E = new EBadIn(__CLASS__, __FUNCTION__, '$firma_oddzizal_id', 'empty');
      throw $E;
    }
    $this->firma_oddzial_id = $firma_oddzial_id;
  }
  /**
   * @param int $bank_oddzial_id
   * @throws EBadIn
   */
  public function setBankOddzialId($bank_oddzial_id){
    if(empty($bank_oddzial_id)){
      $E = new EBadIn(__CLASS__, __FUNCTION__, '$bank_oddzial_id', 'empty');
      throw $E;
    }
    $this->bank_oddzial_id = $bank_oddzial_id;
  }
  public function setDataOd($data_od){
    $this->data_od = $data_od;
  }
  public function setDataDo($data_do){
    $this->data_do = $data_do;
  }
  public function getFirmaOddzialId(){
    return $this->firma_oddzial_id;
  }
  public function getBankOddzialId(){
    return $this->bank_oddzial_id;
  }
  public function getDataOd(){
    return $this->data_od;
  }
  public function getDataDo(){
    return $this->data_do;
  }

	public static function nullRow() {
		return [

            'firma_oddzial_id' => null,
            'bank_oddzial_id'  => null,
            'data_od'          => null,
            'data_do'          => null
		];
	}

}
class BankiOddzialyFirmyOddzialyDependence extends DependenceTableRecord{
  public function tabelaId() {
    return 18;
  }
  public function tableName() {
    return 'banki_oddz_firmy_oddz';
  }
	public function className() {
		return 'BankOddzialFirmaOddzial';
	}
}
class BankiOddzialyFirmyOddzialyTable extends Table{
  public static function getInstance() {
    if(!(self::$instance instanceof BankiOddzialyFirmyOddzialyTable)){
      self::$instance = new BankiOddzialyFirmyOddzialyTable(new BankiOddzialyFirmyOddzialyDependence(),DB::getInstance());
    }
    return self::$instance;
  }
//	/**
//	 * Zwraca tablicę rekordów opisujących oddziały banków i firm połączonych ze sobobą.
//	 * @var array[BankOddzialFirmaOddzial]
//	 */
//	public function getJoinBankFirma($bank_id, $firma_id){
//	}
}