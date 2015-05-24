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
 * @confirm 2014-12-16
 * @prace 2014-10-10 Przenieść kontrolę attrybutów do __set() i __get()
 */
class Administrator extends Encja{
	public static function nullRow() {
		return [
						'login' => NULL,
						'haslo' => NULL,
						'email' => NULL,
						'tel'   => NULL
		];
	}

	/**
   * @param string $login
   * @throws EBadIn
   */
  public function setLogin($login){
    if(empty($login)){
      throw new EBadIn(__CLASS__,__METHOD__,'$login','empty');
    }
    $this->login = $login;
  }
  /**
   * @param string $haslo
   * @throws EBadIn
   */
  public function setHaslo($haslo){
//    if(empty($haslo)){
//      throw new EBadIn(__CLASS__,__METHOD__,'$haslo','empty');
//    }
    $this->haslo = $haslo;
  }
  public function setEmail($email){
    $this->email = $email;
  }
  public function setTel($tel){
    $this->tel = $tel;
  }
	public function getHaslo(){
		return $this->haslo;
	}
	public function getLogin(){
    return $this->login;
  }
  public function getEmail(){
    return $this->email;
  }
  public function getTel(){
    return $this->tel;
  }

}
class AdministratorzyDependence extends DependenceTableRecord{
  public function tabelaId() {
    return 4;
  }
  public function tableName() {
    return 'administratorzy';
  }
	public function className() {
		return 'Administrator';
	}

}
class AdministratorzyTable extends Table{
  public static function getInstance() {
    if(!(self::$instance instanceof AdministratorzyTable)){
      self::$instance = new AdministratorzyTable(new AdministratorzyDependence(),DB::getInstance());
    }
    return self::$instance;
  }
  /**
   * Usuwa rekordy oznaczone jako do usunięcia : z bazy i tego obiektu.
	 * Metodę nadpisano by uniemożliwić usunięcie superadministratora.
	 * @return Table
	 * @prace 2014-10-14 Wprowadzenie preparowanych zapytań PDO
   */
  protected function delete(){
		try{
			foreach ($this->delete as $key => $id) {
				if($id === 1 ){
					continue; // Nie można usunąć superadministratora
				}
				$this->DB->exec($this->queryDelete); // @mysql_query($this->queryDelete);
				unset($this->rows[$id]);
				unset($this->delete[$id]);
				unset($this->records[$id]);
				unset($this->updates[$id]);
			}
		}catch(\Exception $E){
			$this->catchAllTypeException($E);
		}
		return $this;
   }
}