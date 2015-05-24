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
 * @prace 2014-10-18 Duża przebudowa DependencyTableRecord
 * @prace 2014-10-10 Przenieść kontrolę attrybutów do __set() i __get()
 */
class Zarzadca extends Encja{

	public static function nullRow() {
		return [
						'pracownik_id' => NULL,
						'aktywny'      => NULL
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
   * @param int $aktywny
   * @throws EBadIn
   */
  public function setAktywny($aktywny){
    if(empty($aktywny)){
      throw new EBadIn(__CLASS__,__METHOD__,'$aktywny','empty');
    }
    $this->aktywny = $aktywny;
  }
  public function getPracownicyId(){
    return $this->pracownicy_id;
  }
  public function getAktywny(){
    return $this->aktywny;
  }

}
class ZarzadcyDependence extends DependenceTableRecord{
  public function tabelaId() {
    return 7;
  }
  public function tableName() {
    return 'zarzadcy';
  }
	public function className() {
		return 'Zarzadca';
	}
}
class ZarzadcyTable extends Table{
  public static function getInstance() {
    if(!(self::$instance instanceof ZarzadcyTable)){
      self::$instance = new ZarzadcyTable(new ZarzadcyDependence(),DB::getInstance());
    }
    return self::$instance;
  }

}