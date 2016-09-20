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
class StanowiskoStatus extends Encja{
  /**
   * @param int $status
   * @throws EBadIn
   */
  public function setStatus($status){
    if($status === null){
      $E = new EBadIn(__CLASS__, __FUNCTION__, '$status', $status);
      throw $E;
    }
    $this->status = $status;
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
  public function getStatus(){
    return $this->status;
  }
  public function getSymbol(){
    return $this->symbol;
  }
  public function getOpis(){
    return $this->opis;
  }

	public static function nullRow() {
		return [
						'status' => NULL,
						'symbol' => NULL,
						'opis'   => NULL
		];
	}
}
class StanowiskaStatusDependence extends DependenceTableRecord{
  public function tabelaId() {
    return 10;
  }
  public function tableName() {
    return 'stanowiska_status';
  }
	public function className() {
		return 'StanowiskoStatus';
	}
}
class StanowiskaStatusTable extends Table{
  public static function getInstance() {
    if(!(self::$instance instanceof StanowiskaTable)){
      self::$instance = new StanowiskaTable(new StanowiskaDependence(),DB::getInstance());
    }
    return self::$instance;
  }

}