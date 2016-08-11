<?php
// @todo namespace crmsw\db;
// @todo use \pjpl\db\Encja;
use pjpl\depreciate\EBadIn;
use pjpl\db\Where;
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
 * @confirm 2014-08-27 Rzutowanie zmiennych inicjujących int w metodach set...
 */
class Stanowisko extends Encja{

	public static function nullRow() {
		return [
						'symbol'               => NULL,
						'nazwa'                => NULL,
						'placowka_id'          => NULL,
						'pracownik_id'         => NULL,
						'tel'                  => NULL,
						'email'                => NULL,
						'status_stanowiska_id' => NULL,
						'data_od'              => NULL,
						'data_do'              => NULL
		];
	}

  /**
   * @param string $symbol
   * @throws EBadIn
   */
  public function setSymbol($symbol){
    if($symbol === null){
      $E = new EBadIn(__CLASS__, __FUNCTION__, '$symbol', 'empty');
      throw $E;
    }
    $this->symbol = $symbol;
  }
  public function setNazwa($nazwa){
    if($nazwa === null){
      $E = new EBadIn(__CLASS__, __FUNCTION__, '$nazwa', 'empty');
      throw $E;
    }
    $this->nazwa = $nazwa;
  }
  /**
   * @param int $placowka_id
   * @throws EBadIn
   */
  public function setPlacowkaId($placowka_id){
    if($placowka_id === NULL){
      $E = new EBadIn(__CLASS__, __FUNCTION__, '$placowka_id', 'empty');
      throw $E;
    }
    $this->placowka_id = (int)$placowka_id;
  }
  /**
   * @param int $pracownik_id
	 * @confirm 2014-08-27
   */
  public function setPracownikId($pracownik_id){
		if( $pracownik_id === NULL || (int)$pracownik_id === 0 ){
			$this->pracownik_id = NULL;
		}else if( is_numeric((int)$pracownik_id) ){
			$this->pracownik_id = (int)$pracownik_id;
		}else{
			throw new EBadIn(__CLASS__,__FUNCTION__,'pracownik_id',$pracownik_id);
		}
  }
  public function setTel($tel = null){
    $this->tel = $tel;
  }
  public function setEmail($email = null){
    $this->email = $email;
  }
  /**
   * @param string $status_stanowiska_id
   * @throws EBadIn
   */
  public function setStatusStanowiskaId($status_stanowiska_id){
    if($status_stanowiska_id === NULL){
      $E = new EBadIn(__CLASS__, __FUNCTION__, '$status_stanowiska_id', $status_stanowiska_id);
      throw $E;
    }
    $this->status_stanowiska_id = (int)$status_stanowiska_id;
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
  public function getPlacowkaId(){
    return $this->placowka_id;
  }
  public function getPracownikId(){
    return $this->pracownik_id;
  }
  public function getTel(){
    return $this->tel;
  }
  public function getEmail(){
    return $this->email;
  }
  public function getStatusStanowiskaId(){
    return $this->status_stanowiska_id;
  }
  public function getDataOd(){
    return $this->data_od;
  }
  public function getDataDo(){
    return $this->data_do;
  }
}
class StanowiskaDependence extends DependenceTableRecord{
  public function tabelaId() {
    return 9;
  }
  public function tableName() {
    return 'stanowiska';
  }
	public function className() {
		return 'Stanowisko';
	}
}
class StanowiskaTable extends Table{
  public static function getInstance() {
    if( ! (self::$instance instanceof StanowiskaTable)){
      self::$instance = new StanowiskaTable(new StanowiskaDependence(),DB::getInstance());
    }
    return self::$instance;
  }
  /**
   * Zwraca rekord opisujący stanowisko na którym zatrudniony jest pracownik
   * @param int $pracownik_id
   * @return Record
   */
  public function getRecordByPracownikId($pracownik_id){
		$Where = new Where([
				[
						'attribute' => 'pracownik_id',
						'operator'  => '=',
						'value'     => $pracownik_id
				]
		]);
		$this->where($Where)->load();
    return $this->getRecordFirst();
  }
}