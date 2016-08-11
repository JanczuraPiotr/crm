<?php
// @todo namespace crmsw\db
use pjpl\db\a\Encja;
use crmsw\lib\db\a\DependenceTableRecord;
use crmsw\lib\db\a\Table;
use crmsw\lib\db\DB;
use crmsw\lib\db\Record;
/**
 * @package crmsw
 * @subpackage database
 * @author Piotr Janczura <piotr@janczura.pl>
 * @work 2014-10-18 Duża przebudowa DependencyTableRecord
 */

class Tabela extends Encja{
	public static function nullRow() {
		return [
						'nazwa' => NULL,
						'opis'  => NULL
		];
	}
}

class TabelaDependence extends DependenceTableRecord{
  public function tableName() {
    return 'tabele';
  }
  public function tabelaId() {
    return 1;
  }
	public function className() {
		return 'Tabela';
	}
}

class TabeleTable extends Table{
  public function __construct(TabelaDependence $DI, DB $DB) {
    parent::__construct($DI, $DB);
    $this->load();
  }
  public static function getInstance() {
    if(!(self::$instance instanceof TabeleTable)){
      self::$instance = new TabeleTable(new TabelaDependence(),\crmsw\lib\db\DB::getInstance());
    }
    return self::$instance;
  }
  public function getRecordByName($name){
    foreach ($this->records as $id => $row) {
      if($this->getRecord($id)->getData()->getNazwa() === $name){
        return $this->getRecord($id);
      }
    }
    return null;
  }

}