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
class Lider extends Encja{
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
	/**
	 * @param string $nazwa
	 * @throws EBadIn
	 */
	public function setNazwa($nazwa){
    if(empty($nazwa)){
      $E = new EBadIn(__CLASS__, __FUNCTION__, '$nazwa', 'empty');
      throw $E;
    }
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
  public function getStanowiskoId(){
    return $this->stanowisko_id;
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
				'stanowisko_id' => NULL,
				'symbol'        => NULL,
				'nazwa'         => NULL,
				'opis'          => NULL,
				'data_od'       => NULL,
				'data_do'       => NULL

		];
	}
}
class LiderzyDependence extends DependenceTableRecord{
  public function tabelaId() {
    return 12;
  }
  public function tableName() {
    return 'liderzy';
  }
	public static function className() {
		return 'Lider';
	}
}
class LiderzyTable extends Table{
  public static function getInstance() {
    if(!(self::$instance instanceof LiderzyTable)){
      self::$instance = new LiderzyTable(new LiderzyDependence(),DB::getInstance());
    }
    return self::$instance;
  }

}