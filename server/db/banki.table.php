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
class Bank extends Encja{
	public static function nullRow() {
		return [
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
	/**
	 * @param string $nazwa
	 * @throws EBadIn
	 */
  public function setNazwa($nazwa){
    if(empty($nazwa)){
      throw new EBadIn(__CLASS__,__METHOD__,'$nazwa','empty');
    }
		$this->nazwa = $nazwa;
  }
  public function setNip($nip = null){
    $this->nip = $nip;
  }
  public function setKodPoczt($kod_poczt = null){
    $this->kod_poczt = $kod_poczt;
  }
  public function setMiejscowosc($miejscowosc = null){
    $this->miejscowosc = $miejscowosc;
  }
  public function setUl($ul = null){
    $this->ul = $ul;
  }
  public function setNrB($nr_b = null){
    $this->nr_b = $nr_b;
  }
  public function setNrL($nr_l = null){
    $this->nr_l = $nr_l;
  }
  public function setTel($tel = null){
    $this->tel = $tel;
  }
  public function setEmail($email = null){
    $this->email = $email;
  }
  public function setDataOd($data_od = null){
    $this->data_od = $data_od;
  }
  public function setDataDo($data_do = null){
    $this->data_do = $data_do;
  }
  public function getSymbol(){
    return $this->symbol;
  }
  public function getNazwa(){
    return $this->nazwa;
  }
  public function getNip(){
    return $this->nip;
  }
  public function getKodPoczt(){
    return $this->kod_poczt;
  }
  public function getMiejscowosc(){
    return $this->miejscowosc;
  }
  public function getUl(){
    return $this->ul;
  }
  public function getNrB(){
    return $this->nr_b;
  }
  public function getNrL(){
    return $this->nr_l;
  }
  public function getTel(){
    return $this->tel;
  }
  public function getEmail(){
    return $this->email;
  }
  public function getDataOd(){
    return $this->data_od;
  }
  public function getDataDo(){
    return $this->data_do;
  }

}
class BankiDependence extends DependenceTableRecord{
  public function tabelaId() {
    return 15;
  }
  public function tableName() {
    return 'banki';
  }

	public function className() {
		return 'Bank';
	}

}
class BankiTable extends Table{
  public static function getInstance() {
    if(!(self::$instance instanceof BankiTable)){
      self::$instance = new BankiTable(new BankiDependence(),DB::getInstance());
    }
    return self::$instance;
  }

}