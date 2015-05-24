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
 * @prace 2014-10-10 Przenieść kontrolę attrybutów do __set() i __get()
 * @prace 2014-10-18 Duża przebudowa DependencyTableRecord
 */
class Produkt extends Encja{
  /**
   * @param int $bank_id
   * @throws EBadIn
   */
  public function setBankId($bank_id){
    if(empty($bank_id)){
      $E = new EBadIn(__CLASS__, __FUNCTION__, '$bank_id', 'empty');
      throw $E;
    }
    $this->bank_id = $bank_id;
  }
  /**
   * @param int $symbol
   * @throws EBadIn
   */
  public function setSymbol($symbol){
    if(empty($symbol)){
      $E = new EBadIn(__CLASS__, __FUNCTION__, '$symbol', 'empty');
      throw $E;
    }
    $this->symbol = $symbol;
  }
  public function setNazwa($nazwa){
    $this->nazwa = $nazwa;
  }
  public function setOpis($opis){
    $this->opis = $opis;
  }
  public function setDataOd($data_od){
    $this->data_od = $data_od;
  }
  public function setDataDo($data_do){
    $this->data_do = $data_do;
  }
  public function getBankId(){
    return $this->bank_id;
  }
  public function getSymbol(){
    return $this->symbol;
  }
  public function getNazwa(){
    return $this->nazwa;
  }
  public function getOpis(){
    return $this->opis;
  }
  public function getDataOd(){
    return $this->data_od;
  }
  public function getDataDo(){
    return $this->data_do;
  }
	public static function nullRow() {
		return [
            'bank_id' => NULL,
            'symbol'  => NULL,
            'nazwa'   => NULL,
            'opis'    => NULL,
            'data_od' => NULL,
            'data_do'	=> NULL
		];
	}
}
class ProduktyDependence extends DependenceTableRecord{
  public function tabelaId() {
    return 17;
  }
  public function tableName() {
    return 'produkty';
  }
	public function className() {
		return 'Produkt';
	}
}
class ProduktyTable extends Table{
  public static function getInstance() {
    if(!(self::$instance instanceof ProduktyTable)){
      self::$instance = new ProduktyTable(new ProduktyDependence(),DB::getInstance());
    }
    return self::$instance;
  }

}