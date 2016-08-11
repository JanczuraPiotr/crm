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

class Updates extends Encja{
	public static function nullRow() {
		return [
						'tabela_id' => NULL,
						'create'    => NULL,
						'update'    => NULL,
						'delete'    => NULL
		];
	}
}
class UpdatesDependence extends DependenceTableRecord{
  public function tableName() {
    return 'updates';
  }
  public function TabelaId(){
    return 2;
  }
	public function className() {
		return 'Updates';
	}

}
class UpdatesTable extends Table{
  public static function getInstance() {
    if(!(self::$instance instanceof UpdatesTable)){
      self::$instance = new UpdatesTable(new UpdatesDependence(),DB::getInstance());
    }
    return self::$instance;
  }
  public function getArray(){
    $ret = array();
    foreach ($this->rows as $id => $row) {
      $ret[$id] = array('create' => $row['create'], 'update' => $row['update'], 'delete' => $row['delete']);
    }
    return $ret;
  }
  /**
   * Zwraca rekord opisujący tabelę o identyfikatorze $tabela_id
   * @param int $tabela_id - identyfikator tabeli dla której wyszukiwany jest rekord
   * @return UprawnieniaGrup
   */
  protected function findTabela($tabela_id){
    // Poczyniłem założenie, że identyfikator rekordu zawsze będzie taki sam jak identyfikator tabeli która jest opisana w tym rekordzie.
    return $this->getRecord($id);
  }
  public function updateTable($tabela_id,$µs){

  }
}