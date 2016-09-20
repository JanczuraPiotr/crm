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
 * @task 2014-10-10 Przenieść kontrolę attrybutów do __set() i __get()
 * @task 2014-10-18 Duża przebudowa DependencyTableRecord
 */
class StatusStanowiska extends Encja{
  /**
   * @param int $kod
   * @throws EBadIn
   */
  public function setKod($kod){
    if($kod === NULL){
      $E = new EBadIn(__CLASS__, __FUNCTION__, '$kod', $kod);
      throw $E;
    }
    $this->kod = $kod;
  }
  /**
   * @param string $symbol
   * @throws EBadIn
   */
  public function setSymbol($symbol){
    if($symbol === NULL){
      $E = new EBadIn(__CLASS__, __FUNCTION__, '$symbol', $symbol);
      throw $E;
    }
    $this->symbol = $symbol;
  }
  public function setOpis($opis){
    $this->opis = $opis;
  }
	public function getKod(){
		return $this->kod;
	}
	public function getSymbol(){
    return $this->symbol;
  }
  public function getOpis(){
    return $this->opis;
  }

	public static function nullRow() {
		return [
						'kod'    => NULL,
						'symbol' => NULL,
						'opis'   => NULL
		];
	}

}
class StatusStanowiskaDependence extends DependenceTableRecord{
  public function tabelaId() {
    return 10;
  }
  public function tableName() {
    return 'status_stanowiska';
  }
	public function className() {
		return 'StatusStanowiska';
	}
}
class StatusyStanowiskTable extends Table{
  public static function getInstance() {
    if(!(self::$instance instanceof StatusyStanowiskTable)){
      self::$instance = new StatusyStanowiskTable(new StatusStanowiskaDependence(),DB::getInstance());
    }
    return self::$instance;
  }

}