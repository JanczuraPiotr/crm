<?php
namespace crmsw\lib\db;
/**
 * @package crmsw
 * @subpackage database
 * @author Piotr Janczura <piotr@janczura.pl>
 * @done 2014-09-09
 */

class DB extends \pjpl\db\DB{
  public function __construct($dbtype, $dbhost, $dbname, $dbport, $dbuser, $dbpass,$user_group, $charset = null) {
    parent::__construct($dbtype, $dbhost, $dbname, $dbport, $dbuser, $dbpass, $charset);
    $this->user_group = $user_group;
  }
	/**
	 * Zwraca obekt dostępu do bazy danych.1
	 * @param type $dbtype
	 * @param type $dbhost
	 * @param type $dbname
	 * @param type $dbport
	 * @param type $dbuser
	 * @param type $dbpass
	 * @param type $user_group
	 * @param type $charset
	 * @param type $used_tables
	 * @return type
	 */
  public static function init($dbtype, $dbhost, $dbname, $dbport, $dbuser, $dbpass, $user_group = null, $charset = null, $used_tables=null) {
    if(self::$instance !== null){
      return self::$instance;
    }
    self::$instance = new DB($dbtype, $dbhost, $dbname, $dbport, $dbuser, $dbpass, $charset, $user_group);
		self::$instance->_init();

//    if($used_tables !== null ) {
//      // Dostęp do bazy wymagany jest tylko by sprawdzuć czy nastąpiły modyfikacje tabel, których identyfikatory przesłano w tabeli $used_tables.
//      // Nie ma więc potrzeby tworzyć całej infrastruktury obiektu, wykonane zostanie tylko porwównianie czasów modyfikacji tabel znanych klientowi z
//      // czasami zapisanymi w bazie i zwrócona zostanie tabela ze zmianami.
//      return $this->getChangedTable($used_tables);
//    }else{
//      // Pełna inicjacja obietku.
//      self::$instance->_init();
//    }

		return self::$instance;

  }
  private function _init(){
    $this->UprawnieniaGrup = \UprawnieniaGrupTable::getInstance();
    $this->UprawnieniaGrup->load();
    $this->Tables = \TabeleTable::getInstance();
    $this->Tables->load();
//		echo '<pre>'.__FILE__.' '.__LINE__.'<br>'; print_r($this->Tables); echo '</pre>';
  }

  public function getChangedTable($used_tables){
    if( $used_tables !== null){
      // Odwołanie do bazy nastąpiło tylko w celu sprawdzenia czy tabele znane klientowi nie zostały zmodyfikowane.
      // Jeżeli żadna tabela nie została zmodyfikowana zostanie rzucony wyjątek i obiekt nie zostanie utworzony co będzie sygnałęm do zakończenia pracy skrytpu.
      // Jeżeli zostaną znalezione zmodyfikowane tabele to zostaną wpisane do tablicy $this->modified_tables gdzie klucz jest identyfikatorem tabeli w bazie a wartościami są tabele
      // z aktualnymi czasami.
      $this->Updates = \UpdatesTable::getInstance();
      $this->Updates->/*@depreciate*/ __DEPRECIATE__setFiltrKeyValueAndAndRead();
      $this->modified_tables = array();
      foreach ($used_tables as $tabela_id => $timestamp) {
        $rTimeStamp = $this->Updates->getRecord($tabela_id);
        $eTimeStamp = $rTimeStamp->getEncja();
        if($timestamp['create'] !== $eTimeStamp->getCreate()){
          $this->modified_tables[$timestamp]['create'] = $eTimeStamp->getCreate();
        }
        if($timestamp['update'] !== $eTimeStamp->getUpdate()){
          $this->modified_tables[$timestamp]['update'] = $eTimeStamp->getUpdate();
        }
        if($timestamp['delete'] !== $eTimeStamp->getDelete()){
          $this->modified_tables[$timestamp] = $eTimeStamp->getDelete();
        }
      }
    }
    if(count($this->modified_tables) === 0){
      /**
       * @todo Dla czego zwracam tablicę gdy jest ona pusta?
       */
      return $this->modified_tables;
    }
    foreach ($this->modified_tables as $tabela_id => $timestamp) {
      if(isset($this->modified_tables[$tabela_id]['create'])){
        $this->Updates->__DEPRECIATE__setFiltrStringAndRead('create > '.$this->modified_tables[$tabela_id]['create'].' AND tabela_id = '.$tabela_id);// @depreciate
        $this->modified_tables[$tabela_id]['create']['ids'] = $this->Updates->getIds();
      }
      if(isset($this->modified_tables[$tabela_id]['update'])){
        $this->Updates->__DEPRECIATE__setFiltrStringAndRead('update > '.$this->modified_tables[$tabela_id]['create'].' AND tabela_id = '.$tabela_id);// @depreciate
        $this->modified_tables[$tabela_id]['updata']['ids'] = $this->Updates->getIds();
      }
      if(isset($this->modified_tables[$tabela_id]['delete'])){
        $this->Updates->__DEPRECIATE__setFiltrStringAndRead('delete > '.$this->modified_tables[$tabela_id]['create'].' AND tabela_id = '.$tabela_id);// @depreciate
        $this->modified_tables[$tabela_id]['delete']['ids'] = $this->Updates->getIds();
      }
    }
    return $this->modified_tables;
  }
  /**
   * Czy zalogowany użytkownik może wstawiać rekordy do tabeli o podanym identyfikatorze
   * @param int $tabela_id - identyfikator tableli
   * @return boolean
   */
  public function canCreate($tabela_id){
    return $this->UprawnieniaGrup->canCreate($tabela_id, $this->user_group);
  }
  /**
   * Czy zaloogowany użytkownik może czytać tabelę o podanym identyfikatorze
   * @param int $tabela_id - identyfikator tableli
   * @return boolean
   */
  public function canRead($tabela_id){
    return $this->UprawnieniaGrup->canRead($tabela_id, $this->user_group);
  }
  /**
   * Czy zalogowany użytkownik może aktializować rekordy w tabeli o podanum udentyfikatorze
   * @param int $tabela_id - identyfikator tableli
   * @return boolean
   */
  public function canUpdate($tabela_id){
    return $this->UprawnieniaGrup->canUpdate($tabela_id, $this->user_group);
  }
  /**
   * Czy zalogowany użytkownik może kasować rekordy z tabeli o podanym identyfikarze
   * @param int $tabela_id - identyfikator tableli
   * @return boolean
   */
  public function canDelete($tabela_id){
    return $this->UprawnieniaGrup->canDelete($tabela_id, $this->user_group);
  }
  /**
   * Wywoływana zwrotnie przez tabelę która wykonała modyfikacje.
   * @param int $tabela_id - identyfikator tabeli
   * @param type $µs
   */
  public function doneCreating($tabela_id,$µs){
		// @task 2014-01-01 Dokończ tak by kolumna "create" tabeli "updates" rekordu o numerze $tabela_id była zaktualizowana wartością zmiennej $µs
  }
  /**
   * Wywoływana zwrotnie gdy przez tabelę która skasowała rekord
   * @param int $tabela_id - identyfikator tableli
   * @param type $µs
   */
  public function doneDeleting($tabela_id,$µs){
		// @task 2014-01-01 Dokończ tak by kolumna "update" tabeli "updates" rekordu o numerze $tabela_id była zaktualizowana wartością zmiennej $µs
  }
  /**
   * Wywoływana zwrotnie gdy tabelea uaktualniła rekord
   * @param int $tabela_id - identyfikator tableli
   * @param int $µs
   */
  public function doneUpdating($tabela_id,$µs){
		// @task 2014-01-01 Dokończ tak by kolumna "delete" tabeli "updates" rekordu o numerze $tabela_id była zaktualizowana wartością zmiennej $µs
  }
  /**
   * @return \AdministratorzyTable
   */
  public function tableAdministratorzy(){
    return \AdministratorzyTable::getInstance();
  }
  /**
   * @return \BankiTable
   */
  public function tableBanki(){
    return \BankiTable::getInstance();
  }
  /**
   * @return \BankiOddzialyTable
   */
  public function tableBankiOddzialy(){
    return \BankiOddzialyTable::getInstance();
  }
  /**
   * @return \BankiOddzialyFirmyOddzialyTable
   */
  public function tableBankiOddzialyFirmyOddzialy(){
    return \BankiOddzialyFirmyOddzialyTable::getInstance();
  }
  /**
   * @return \DokumentZadaniaTable
   */
  public function tableDokumentyZadania(){
    return \ZadaniaDokumentyTable::getInstance();
  }
  /**
   * @return \DokumentProduktuTable
   */
  public function tableDokumentyProduktu(){
    return \DokumentyProduktuTable::getInstance();
  }
  /**
   * @return \DokumentSlownikTable
   */
  public function tableDokumentySlownik(){
    return \DokumentySlownikTable::getInstance();
  }
  /**
   * @return \FirmyTable
   */
  public function tableFirmy(){
    return \FirmyTable::getInstance();
  }
  /**
   * @return \FirmyOddzialyTable
   */
  public function tableFirmyOddzialy(){
    return \FirmyOddzialyTable::getInstance();
  }
  /**
   * @return \KlienciTable
   */
  public function tableKlienci(){
    return \KlienciTable::getInstance();
  }
  /**
   * @return \StatusyKlientowTable
   */
  public function tableStatusyKlientow(){
    return \StatusyKlientowTable::getInstance();
  }
  /**
   * @return \StatusyZadanTable
   */
  public function tableStatusyZadan(){
    return \StatusyZadanTable::getInstance();
  }
	/**
   * @return \LiderzyTable
   */
  public function tableLiderzy(){
    return \LiderzyTable::getInstance();
  }
  /**
   * @return  \OsobyPowiazaneTable
   */
  public function tableOsobyPowiazane(){
    return \OsobyPowiazaneTable::getInstance();
  }
	/**
	 * @return \PochodzenieKlientowTable
	 */
	public function tablePochodzenieKlientow(){
		return \PochodzenieKlientowTable::getInstance();
	}
	/**
   * @return \PracownicyTable
   */
  public function tablePracownicy(){
		echo '<pre>'.__FILE__.' '.__LINE__.'<br>'; print_r(\PracownicyTable::getInstance()); echo '</pre>';
    return \PracownicyTable::getInstance();
  }
  /**
   * @return \ProduktyTable
   */
  public function tableProdukty(){
    return \ProduktyTable::getInstance();
  }
  /**
   * @return \StanowiskaTable
   */
  public function tableStanowiska(){
    return \StanowiskaTable::getInstance();
  }
  /**
   * @return \StanowiskaTable
   */
  public function tableStatusyStanowisk(){
    return \StatusyStanowiskTable::getInstance();
  }
  /**
   * @return \ZadaniaTable
   */
  public function tableZadania(){
    return \ZadaniaTable::getInstance();
  }
  /**
   * @return \ZadaniaFirmyTable
   */
  public function tableZadaniaFirmy(){
    return \ZadaniaFirmyTable::getInstance();
  }
  /**
   * @return \ZadaniaOpisyTable
   */
  public function tableZadaniaOpis(){
    return \ZadaniaOpisyTable::getInstance();
  }
  /**
   * @return \ZarzadcyTable
   */
  public function tableZarzadcy(){
    return \ZarzadcyTable::getInstance();
  }
  /**
   * @return \ZarzadyTable
   */
  public function tableZarzady(){
    return \ZarzadyTable::getInstance();
  }
  /**
   * @return \ZatrudnieniaTable
   */
  public function tableZatrudnienia(){
    return \ZatrudnieniaTable::getInstance();
  }
  /**
   * @return \ZespolyTable
   */
  public function tableZespoly(){
    return \ZespolyTable::getInstance();
  }

  /**
   * Tablica której klucz jest identyfikatorem tabelicy bazy danych a wartościami są aktualne czasy tej tabeli
   * @var array
   */
  private $modified_tables = null;
  /**
   * Tabela nazw i identyfikatorów tabel
   * @var TabelaTable
   */
  private $Tables;
  /**
   * Tabela uprawnień grup
   * @var UprawnieniaGrupTable
   */
  private $UprawnieniaGrup;
  /**
   * Tabel z czasami aktualizaji tabel
   * @var UpdatesTable
   */
  private $Updates;
  /**
   * Identyfikator grupy do której należy zalowgowany użytkownik
   * @var int
   */
  private $user_group;
}