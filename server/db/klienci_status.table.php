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
 * @todo Status klienta powinien być usunięty a status ma być nadawany zadaniom jakie są klientowi przydzielone. Jeżeli klient nie życzy sobie żadnych ofert to jego data_do powinna być ustawiona
 */
class KlientStatus extends Encja{
	public static function nullRow() {
		return [
						'status' => NULL,
						'opis'   => NULL
		];
	}
}
class KlienciStatusyDependence extends DependenceTableRecord{
  public function tabelaId() {
    return 20;
  }
  public function tableName() {
    return 'klienci_status';
  }
	public function className() {
		return 'KlientStatus';
	}
}
class KlienciStatusyTable extends Table{
  public static function getInstance() {
    if(!(self::$instance instanceof KlienciStatusyTable)){
      self::$instance = new KlienciStatusyTable(new KlienciStatusyDependence(),DB::getInstance());
    }
    return self::$instance;
  }
}