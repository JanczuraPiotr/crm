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
 * @confirm 2014-12-16
 * @prace 2014-10-10 Przenieść kontrolę attrybutów do __set() i __get()
 */
class FirmaOddzial extends Encja{
	public static function nullRow() {
		return [
				'firma_id'    => NULL,
				'symbol'      => NULL,
				'nazwa'       => NULL,
				'nip'         => NULL,
				'kod_poczt'   => NULL,
				'miejscowosc' => NULL,
				'ul'          => NULL,
				'nr_b'        => NULL,
				'nr_l'        => NULL,
				'tel'         => NULL,
				'email'       => NULL,
				'data_od'     => NULL,
				'data_do'     => NULL
		];
	}

//	/**
//   * @param int $firma_id
//   * @throws EBadIn
//   */
//  public function setFirmaId($firma_id){
//    if(empty($firma_id)){
//      throw new EBadIn(__CLASS__,__METHOD__,'$firma_id','empty');
//    }
//    $this->firma_id = $firma_id;
//  }
//  /**
//   * @param int $symbol
//   * @throws EBadIn
//   */
//  public function setSymbol($symbol){
//    if(empty($symbol)){
//      throw new EBadIn(__CLASS__,__METHOD__,'$symbol','empty');
//    }
//    $this->symbol = $symbol;
//  }
//	/**
//	 * @param string $nazwa
//	 * @throws EBadIn
//	 */
//  public function setNazwa($nazwa){
//    if(empty($nazwa)){
//      throw new EBadIn(__CLASS__,__METHOD__,'$nazwa','empty');
//    }
//    $this->nazwa = $nazwa;
//  }
//  public function setNip($nip = null){
//    $this->nip = $nip;
//  }
//  public function setKodPoczt($kod_poczt = null){
//    $this->kod_poczt = $kod_poczt;
//  }
//  public function setMiejscowosc($miejscowosc = null){
//    $this->miejscowosc = $miejscowosc;
//  }
//  public function setUl($ul = null){
//    $this->ul = $ul;
//  }
//  public function setNrB($nr_b = null){
//    $this->nr_b = $nr_b;
//  }
//  public function setNrL($nr_l = null){
//    $this->nr_l = $nr_l;
//  }
//  public function setTel($tel = null){
//    $this->tel = $tel;
//  }
//  public function setEmail($email = null){
//    $this->email = $email;
//  }
//  public function setDataOd($data_od = null){
//    $this->data_od = $data_od;
//  }
//  public function setDataDo($data_do = null){
//    $this->data_do = $data_do;
//  }
//  public function getFirmaId(){
//    return $this->firma_id;
//  }
//  public function getSymbol(){
//    return $this->symbol;
//  }
//  public function getNazwa(){
//    return $this->nazwa;
//  }
//  public function getNip(){
//    return $this->nip;
//  }
//  public function getKodPoczt(){
//    return $this->kod_poczt;
//  }
//  public function getMiejscowosc(){
//    return $this->miejscowosc;
//  }
//  public function getUl(){
//    return $this->ul;
//  }
//  public function getNrB(){
//    return $this->nr_b;
//  }
//  public function getNrL(){
//    return $this->nr_l;
//  }
//  public function getTel(){
//    return $this->tel;
//  }
//  public function getEmail(){
//    return $this->email;
//  }
//  public function getDataOd(){
//    return $this->data_od;
//  }
//  public function getDataDo(){
//    return $this->data_do;
//  }
}
class FirmyOddzialyDependence extends DependenceTableRecord{
  public function tabelaId() {
    return 6;
  }
  public function tableName() {
    return 'firmy_oddzialy';
  }
	public function className() {
		return 'FirmaOddzial';
	}
}
class FirmyOddzialyTable extends Table{
  public static function getInstance() {
    if( ! (self::$instance instanceof FirmyOddzialyTable)){
      self::$instance = new FirmyOddzialyTable(new FirmyOddzialyDependence(),DB::getInstance());
    }
    return self::$instance;
  }

}