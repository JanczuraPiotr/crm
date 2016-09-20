<?php
namespace pjpl\db;
/**
 * Zarządzanie dostępem do bazy danych w oparciu o obiekt \PDO.
 * Obiekt obsługuje transakcje zagnieżdżone.
 * @package pl.janczura.piotr
 * @subpackage database
 * @author <piotr@janczura.pl>
 * @done 2013-11-05 - Zniana CDB na CDBmysql i utworzenie nowego DB z obsługą \PDO
 */
class DB extends \PDO{
  /**
   * Tworzy obiekt dostępu do bazy danych
   * @param string $dbtype - typ bazy danych np : "mysql"
   * @param string $dbhost - adres komputera na którym znajduje się baza danych np: "localhost"
   * @param string $dbname - nazwa bazy danych
   * @param string $dbport - port TCP na którym nasłuchuje serwer (3306)
   * @param string $dbuser - nazwa użytkwonika
   * @param string $dbpass - hasło użytkownika
   * @throw \E\PDOException  - nie nawiązano połączenia z bazą danych
   */
  public function __construct($dbtype,$dbhost,$dbname,$dbport,$dbuser,$dbpass,$charset = 'utf8'){
    parent::__construct($dbtype.':host='.$dbhost.';dbname='.$dbname.';port='.$dbport,$dbuser,$dbpass);
    $this->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    $this->query("set names $charset");
    $this->transaction=0;
    $this->rollback=false;
    $this->dbtype = $dbtype;
    $this->dbhost = $dbhost;
    $this->dbname = $dbname;
    $this->dbport = $dbport;
    $this->dbuser = $dbuser;
    $this->dbpass = $dbpass;
  }
  /**
   * Zwraca obekt dostępu do bazy danych.
   * @param string $dbtype - typ bazy danych np : "mysql"
   * @param string $dbhost - adres komputera na którym znajduje się baza danych np: "localhost"
   * @param string $dbname - nazwa bazy danych
   * @param string $dbport - port TCP na którym nasłuchuje serwer (3306)
   * @param string $dbuser - nazwa użytkwonika
   * @param string $dbpass - hasło użytkownika
   * @throw \E\PDOException  - nie nawiązano połączenia z bazą danych
   */
  public static function init($dbtype,$dbhost,$dbname,$dbport,$dbuser,$dbpass){
    if(self::$instance === null){
      self::$instance = new DB($dbtype, $dbhost, $dbname, $dbport, $dbuser, $dbpass);
    }
    return self::$instance;
  }
  /**
   * @return DB
   */
  public static function getInstance(){
    return self::$instance;
  }
  /**
   * Uruchamia transakcję zagnieżdżoną
   */
  public function beginTransaction(){
    if($this->transaction === 0){
      if( !parent::beginTransaction()){
        /**
         * @todo Obsłużyć błąd nie uruchomienia transakcji
         */
      }
    }
    $this->transaction++;
    return $this;
  }
  /**
   * Zatwierdza transakcję ze wszystkimi zagnieżdżonymi zapytanami
   */
  public function commit(){
    $this->transaction--;
    if($this->transaction === 0){
      if($this->rollback === false){
        // W czasie zwijania transakcji nie wykonano wycofywania więc ostatecznie można całość zatwierdzić
        parent::commit();
      }else{
        // W czasie zwijania transakcji wykonano operację wycofywania transakcji więc ostatecznie należy wycofać całość
        parent::rollBack();
      }
    }
    return $this;
  }
  /**
   * Wycofuje transakcję zagnieżdżoną ze wszystkimi zagnieżdżonymi zapytaniami
   */
  public function rollBack(){
    $this->transaction--;
    $this->rollback=true;
    if($this->transaction === 0 ){
      parent::rollBack();
    }
    return $this;
  }
  /**
   * Zwraca identyfikator jaki zostanie przyznany w tabeli dla nowego rekordu
   * @param string $tabela - nazwa tabeli
   * @return int
   */
  public function next_insert_id($tabela){
    $sql = "SHOW TABLE STATUS LIKE '$tabela'";
    $stmt = $this->query($sql);
    $row = $stmt->fetch(\PDO::FETCH_ASSOC);
    return $row['Auto_increment'];
}

  /**
   * Licznik otwartych transakcji
   * @var int
   */
  private $transaction;
  /**
   * Znacznik wycofania którejś z zagnieżdżonych transakcji.
   * Jeżeli wycofano choć jedną zagnieżdżoną transakcjię cały blok zostanie wycofany
   * @var boolean
   */
  private $rollback;

  private $dbtype;
  private $dbhost;
  private $dbname;
  private $dbport;
  private $dbuser;
  private $dbpass;
  /**
   * @var DB
   */
  protected static $instance = null;

  //------------------------------------------------------------------------------
  // Interfejs dla zwykłych wywołań bazy mysql na czas migracji systemu do \PDO
  /**
   * Emulator metody mysql_query()
   * @param string $query
   * @return \PDOStatement
   */
	// @depreciate
  public function DEPRECIATE_mysql_query($query){
    $stmt = $this->prepare($query);
    $stmt->execute();
    return $stmt;
    //return $this->query($query);
  }
  /**
   * Emulator metody mysql_error()
   * @return int
   */
	// @depreciate
  public function DEPRECIATE_mysql_error(){
    return $this->errorInfo();
  }
  /**
   * Emulator metody mysql_errno()
   * @return string Opis błędu
   */
	// @depreciate
  public function DEPRECIATE_mysql_errno(){
    $ret = $this->errorCode();
    return $ret[2];
  }
  /**
   * Emulator metody mysql_insert_id()
   * @return int
   */
	// @depreciate
  public function DEPRECIATE_mysql_insert_id(){
    return $this->lastInsertId();
  }
	// @todo Czemu tu są referencje w przekazaniu parametru który jest obiektem ??
	// @depreciate
  public function DEPRECIATE_mysql_fetch_array(\PDOStatement &$stmt){
    return $stmt->fetch(\PDO::FETCH_NUM);
  }
	// @depreciate
  public function DEPRECIATE_mysql_fetch_assoc(\PDOStatement &$stmt){
    return $stmt->fetch(\PDO::FETCH_ASSOC);
  }
}