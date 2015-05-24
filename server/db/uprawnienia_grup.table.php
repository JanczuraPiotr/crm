<?php
// @todo namespace crmsw\db;
use pjpl\db\a\Encja;
use crmsw\lib\db\a\DependenceTableRecord;
use crmsw\lib\db\DB;
use crmsw\lib\db\Record;
/**
 * @package crmsw
 * @subpackage database
 * @author Piotr Janczura <piotr@janczura.pl>
 * @prace 2014-10-18 Duża przebudowa DependencyTableRecord
 */

class UprawnieniaGrup extends Encja{
	public static function nullRow() {
		return [
						'tabela_id'    => NULL,
						'create_right' => NULL,
						'read_right'   => NULL,
						'update_right' => NULL,
						'delete_right' => NULL
		];
	}
}
class UprawnieniaGrupDependence extends DependenceTableRecord{
  public function TabelaId(){
    return 3;
  }
  public function tableName() {
    return 'uprawnienia_grup';
  }
	public function className(){
		return 'UprawnieniaGrup';
	}
}
class UprawnieniaGrupTable extends crmsw\lib\db\a\Table{
  public static function getInstance() {
    if(!(self::$instance instanceof UprawnieniaGrupTable)){
      self::$instance = new UprawnieniaGrupTable(new UprawnieniaGrupDependence(), DB::getInstance());
    }
    return self::$instance;
  }
  public function canCreate($tabela_id,$group){
    if($this->findTabela($tabela_id)->getEncja()->create_right & $group ){
      return true;
    }else{
      return false;
    }
  }
  public function canRead($tabela_id,$group){
    if($this->findTabela($tabela_id)->getEncja()->read_right & $group ){
      return true;
    }else{
      return false;
    }
  }
  public function canUpdate($tabela_id,$group){
    if($this->findTabela($tabela_id)->getEncja()->update_right & $group ){
      return true;
    }else{
      return false;
    }
  }
  public function canDelete($tabela_id,$group){
    if($this->findTabela($tabela_id)->getEncja()->delete_right & $group ){
      return true;
    }else{
      return false;
    }
  }
  /**
   * Zwraca rekord opisujący tabelę o identyfikatorze $tabela_id
   * @param int $tabela_id - identyfikator tabeli dla której wyszukiwany jest rekord
   * @return UprawnieniaGrup
   */
  protected function findTabela($tabela_id){
    // Poczyniłem założenie, że identyfikator rekordu zawsze będzie taki sam jak identyfikator tabeli która jest opisana w tym rekordzie.
    return $this->getRecord($tabela_id);
  }
}