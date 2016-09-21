<?php
namespace pjpl\db\a;
use pjpl\db\a\Encja;
use pjpl\db\Record;
use pjpl\db\Where;
/**
 * @done 2014-12-19
 * @todo Rozważyć czy ponowne wywołanie A_DBTable::read() powinno bez ostrzeżeń czyścić wszystkie tablice przed wczytaniem nowych rekordów
 * @todo Filtry i czytanie z tabeli powinno pozwalać określić czy dotyczą tabeli w bazie czy zbioru pobranego wcześniej i zapianego w tej tabeli
 *				Można dodać metodę odłączającą tabelę od bazy danych na czas wykonywania zapytań na zgromadzonych danych
 * @doc 2014-10-27
 */
abstract class Table{
  /**
   * @return Table
   */
  abstract static function getInstance();
  public function __construct($DI, $DB) {
    $this->setDI($DI);
    $this->DB = $DB;
		$this->Where = new Where();
  }
  public function __destruct() {
    $this->flush();
  }
  /**
   * Wstawia encję do obiektu.
   * Do czasu wywołania $this->create() przechowywana jest w buforze.
   * W momencie wymuszenia zapisu do bazy danych na podstawie bufora utworzone zostnią rekordy.
   * @param Encja $Encja
   */
  public function addEncja(Encja $Encja){
    array_push($this->newEncja, $Encja);
  }
  public function addEncjaArray(array $Encje){
    foreach ($Encje as $key => $Encja) {
      array_push($this->newEncja, $Encja);
    }
		return $this;
  }
	/**
	 * Ilość wczytanych rekordów
	 * @return int
	 */
  public function count(){
    return count($this->rows);
  }
	/**
	 * Ilość rekordów, które zwróciło by zapytanie gdyby nie było okraniczone klauzulą limit x,x.
	 * Wartość jest aktualizowana po funkcjach loadStartLimit...
	 * Inne funkcje nie resetują tej zmiennej.
	 * @return bigint
	 */
	public function countFiltered(){
		return $this->count_filtered;
	}
	/**
	 * Ilość wszystkich rekordów w tabeli bazy danych
	 * @return int
	 */
	public function countTotal(){
		try{
			$query = "SELECT COUNT(id) FROM ".$this->DI->tableName();
			$this->LastPDOStatement = $this->DB->query($query);
			$rec = $this->LastPDOStatement->fetch(\PDO::FETCH_BOTH);
			return (int)$rec[0];
		}catch(\Exception $E){
			$this->catchAllTypeException($E);
		}
		return -1;
	}
	/**
   * Tworzy rekord na podstawie przekazanej encji.
   * Obiekt $Encja nie trafia do bufora wewnętrznego lecz od razu zapisywana jest w bazie danych
   * @param Encja $Encja - tablica Encji
   * @return Record - Record utworzony na podstawie Encji
	 * @task 2014-10-14 Wprowadzenie preparowanych zapytań PDO
   */
  public function createRecordImmediately(Encja $Encja){
		try{
			$ret = array();
			$this->LastPDOStatement = $this->DB->prepare($this->queryCreate);
			$this->LastPDOStatement->execute($this->DI->prepareParamsCreate($Encja));
			$newid = $this->DB->lastInsertId();
			return $this->getRecord($newid);
		}  catch (\Exception $E){
			$this->catchAllTypeException($E);
		}
		return null;
  }
  /**
   * Tworzy rekordy na podstawie przekazanej tablicy Encji.
   * Encje nie są wstawioane do bufora wewnętrznego lecz od razu zapisywane są w bazie.
   * @param array $Encja
   * @return array - tablica w której klucz wskazuje na Encję a wartość wskazuje na rekord który powstał na jej podstwie
	 * @task 2014-10-14 Wprowadzenie preparowanych zapytań PDO
   */
  public function createRecordsArrayImmediately(array $Encje){
		try{
			$ret = array();
			foreach ($Encje as $key => $Encja) {
				$this->prepareQueryCreate($Encja);
				$this->DB->exec($this->queryCreate);
				$newid = $this->DB->lastInsertId();
				$ret[$Encja] = $this->getRecord($newid);
			}
			return $ret;
		}  catch (\Exception $E){
			$this->catchAllTypeException($E);
		}
		return null;
  }
	/**
	 * @param type $id
	 * @return Table
	 */
  public function deleteId($id){
    $this->delete[] = $id;
		return $this;
  }
	/**
	 * @param type $id
	 * @return Table
	 * @task 2014-10-14 Wprowadzenie preparowanych zapytań PDO
	 */
  public function deleteIdImmediately($id){
		try{
			$this->LastPDOStatement = $this->DB->prepare($this->queryDelete);
			$this->LastPDOStatement->bindParam(':id', $id, \PDO::PARAM_INT);
			$this->LastPDOStatement->execute();
		}catch(\Exception $E){
			// @err 2014-10-20 Błąd zależności klucza obcego nie jest raportowany w komunikacie opuszczającym klasę biznesową
			$this->catchAllTypeException($E);
		}
		return $this;
  }
  /**
   * Zapisz wszystkie zmiany w bazie i opróżnij obiekt.
	 * @return Table
   */
  public function flush(){
    if(count($this->updates) > 0){
      $this->update();
      $this->updates = array();
    }
    if(count($this->delete) > 0){
      $this->delete();
      $this->delete = array();
    }
    if(count($this->newEncja) > 0){
      $this->create();
      $this->newEncja = array();
    }
    $this->rows = array();
    $this->records = array();
    $this->fresh = array();
    return $this;
  }
	/**
	 * @return DependenceTableRecord
	 */
	public function getDI(){
		return $this->DI;
	}
	/**
   * Zwraca identyfikatory rekordów wczytanych do obiektu
   */
  public function getIds(){
    return array_keys($this->rows);
  }
	/**
	 * Zwraca record gdy w wyniku zapytania otrzymano jeden wynik.
	 * Gdy zapytanie zwróciło większą ilość rekordów lub nie zwróciło żadnego rekordu to zwraca null
	 * @return Record || null
	 */
	public function getRecordIfOne(){
		if($this->count() === 1){
			return $this->getRecordFirst();
		}  else {
			return NULL;
		}
	}
	/**
   * Pierwszy rekord ze zbioru odpowiedzi.
   * @return Record || null
   */
  public function getRecordFirst(){
    reset($this->rows);
    if($row = current($this->rows)){
      return $this->getRecord($row['id']);
    }
    return null;
  }
	/**
	 * @return Record || null
	 */
  public function getRecordLast(){
    if(count($this->rows)){
			$row = end($this->rows);
      return $this->getRecord($row['id']);
    }
    return null;
  }
  /**
   * Następny rekord ze zbioru odpowiedzi.
   * @return Record || null
   */
  public function getRecordNext(){
    if($row = next($this->rows)){
      return $this->getRecord($row['id']);
    }
    return null;
  }
  /**
   * Zwróć obiekt Record dla rekordu o identyfikaorze $id
   * @param int $id
   */
  public function getRecord($id){
		if( ! isset($id) ){
			return NULL;
		}
    if( ! isset($this->records[$id]) ){
      if( ! isset($this->rows[$id]) ){
        $this->readRow($id);
      }
      $this->records[$id] = $this->DI->fromRowToRecord($this->rows[$id], $this);  //Record($id,$this->DI->fromRowToEncja($this->rows[$id]), $this );
    }
    return $this->records[$id];
  }
	/**
	 * Referencja do obiektu klauzuli where
	 * @return Where
	 * @done 2014-09-26
	 */
	public function getWhere(){
		return $this->Where;
	}
	/**
	 * Ustawia zakres rekordów obsługiwanych w następnych zapytaniach.
	 * Limit jest nakładany gdy zmienna $limit > 0
	 * @param int $start
	 * @param int $limit
	 * @return \pjpl\db\a\Table
	 * @task 2014-10-14 Dodano modyfikację zmiennej $this->limit_string
	 * @done 2014-09-26
	 */
	public function limit($start, $limit){
		if($start >= 0 ){
			$this->start = $start;
		}  else {
			$this->start = 0;
		}
		if($limit > 0 ){
			$this->limit = $limit;
		}  else {
			$this->limit = 0;
		}
		$this->limit = $limit;

		$this->limit_string = ( $this->limit > 0 ? ' LIMIT '.$this->start.' , '.$this->limit : '' );
		return $this;
	}
	/**
	 * Wczytuje rekordy na podstawie wcześniejszej konfuifuracji metodami where() i limit()
	 * @return Table
	 * @task 2014-10-14 Wprowadzenie preparowanych zapytań PDO
	 * @done 2014-09-26
	 */
	public function load(){
		$this->read();
		return $this;
	}
  /**
   * Zwróci wartość jaka zostanie nadana dodanemu identyfikatorow rekordu
   * @return int
   */
  public function nextAutoincrementId(){
//    $next_increment = $this->DB->query("SHOW TABLE STATUS LIKE '".$this->DI->tableName())."'";
		$this->LastPDOStatement = $this->DB->query("SELECT AUTO_INCREMENT FROM information_schema.TABLES WHERE TABLE_NAME = '".$this->DI->tableName()."'");
		$rec = $this->LastPDOStatement->fetch(\PDO::FETCH_ASSOC);
    return $rec['AUTO_INCREMENT'];
  }
  /**
   * Zapisz wszystkie zmiany w bazie
	 * @return Table
   */
  public function save(){
    $this->create();
    $this->update();
    $this->delete();
		return $this;
  }

	/**
	 * Przekazuje warunek where który będzie wykorzystywany w każdym następnum zapytaniu do bazy danych
	 * @param Where $Where
	 * @return Table
	 */
	public function where(Where $Where){
		if( $Where  ){
			$this->Where = $Where;
		}else{
			$this->Where = new Where();
		}
		return $this;
	}
	/**
	 * @return string
	 */
  public function tableName(){
    return $this->DI->tableName();
  }
  /**
   * Oznacza rekord jako przeznaczony do aktualizacji
   * @param Record $Record
	 * @return Table
   */
  public function updateRecord(Record $Record){
    $this->updates[] = $Record->getId();
		return $this;
  }
	/**
	 * @param array $Records
	 * @return Table
	 */
  public function updateRecordsArray(array $Records){
    foreach ($Records as $key => $Record) {
      $this->updates[] = $key;
    }
		return $this;
  }
	/**
	 * @param Record $Record
	 * @return Table
	 * @task 2014-10-14 Wprowadzenie preparowanych zapytań PDO
	 */
  public function updateRecordImmediately(Record $Record){
		try{
			$this->LastPDOStatement = $this->DB->prepare($this->queryUpdate);
			$this->LastPDOStatement->execute($this->DI->prepareParamsUpdate($Record));
			$this->readRow($Record->getId());
		}catch(\Exception $E){
			$this->catchAllTypeException($E);
		}
		return $this;
  }
  /**
	 * @param array $Records
	 * @return Table
	 * @task 2014-10-14 Wprowadzenie preparowanych zapytań PDO
	 */
	public function updateRecordsArrayImmediately(array $Records){
		try{
			$this->LastPDOStatement = $this->DB->prepare($this->queryUpdate);
			foreach ($Records as $key => $Record) {
				$this->LastPDOStatement->execute($this->DI->prepareParamsUpdate($Record));
				$this->readRow($key);
			}
		}  catch (\Exception $E){
			$this->catchAllTypeException($E);
		}
		return $this;
  }

  //------------------------------------------------------------------------------
  // protected
  //

  /**
   * Tworzy rekordy w tabeli bazy danych z obektów dodanych metodą add(Encja $Data).
   * Po zapisaniu rekordu w bazie danych obiekt $Data usuwany jest z listy nowych danych : $this->newData i wraz z identyfikatorem jaki otrzymał nowo utworzony rekord
   * tworzony jest obiekt klasy Record.
	 * @return Table
	 * @done 2014-10-14 Wprowadzenie preparowanych zapytań PDO
   */
  protected function create(){
		try{
			$this->LastPDOStatement = $this->DB->prepare($this->queryCreate);
			foreach ($this->newEncja as $key => $Encja) {
				foreach ($this->DI->prepareParamsCreate($Encja) as $key => $value) {
					$this->LastPDOStatement->bindParam($key,$value);// @todo wprowadzić kontrolę typów na podstawie danych zgromadzonych w encji - rozbudować encję
				}
				$this->LastPDOStatement->execute();
				$newid = $this->DB->lastInsertId();
				$this->records[$newid] = new Record($newid, $Encja,  $this);
				unset($this->newEncja[$key]); // ! usuwam wpis w tablicy a nie obiekt tam wpisany. Obiekt został już wpisany do $this->records
				$this->getRecord($newid);
			}
		}catch(\Exception $E){
			$this->catchAllTypeException($E);
		}
		return $this;
	}
  /**
   * Usuwa rekordy oznaczone jako do usunięcia : z bazy i tego obiektu
	 * @return Table
	 * @task 2014-10-14 Wprowadzenie preparowanych zapytań PDO
   */
  protected function delete(){
		try{
			$this->LastPDOStatement = $this->DB->prepare($this->queryDelete);
			foreach ($this->delete as $key => $id) {
				$this->LastPDOStatement->bindParam(':id', $id, \PDO::PARAM_INT);
				$this->LastPDOStatement->execute();
				unset($this->rows[$id]);    // Usuwam skasowany rekord z podręcznej tablicy
				unset($this->delete[$id]);  // Usuwam informację o konieczności skasowania
				unset($this->records[$id]); // Usuwam ewentualny obiekt Data gdyby został utworzony.
				unset($this->updates[$id]); // Nie ma pewności że zanim podjęto decyzję o usuwaniu obiekt nie został zmodyfikowany a przez to trafił do tej tablicy
			}
		}catch(\Exception $E){
			$this->catchAllTypeException($E);
		}
		return $this;
   }
  /**
   * Odczyt z bazy danych na podstawie wcześniej spreparowanego zapytania.
   * Operacja czyści wewnętrzne tabele i inicjuje je wszystkie nowymi wartościami.
	 * @return Table
	 * @done 2014-10-21 Wprowadzenie preparowanych zapytań PDO
   */
  protected function read(){
    // @todo Pozostawić czyszczenie poprzez inicjowanie tabel czy przejżeć każdą i usunąć każdy jej element indywidualnie.
    $this->rows = array();
    $this->records = array();
    $this->newEncja = array();
    $this->updates = array();
    $this->delete = array();
		try{
			// Ta metoda wykonuje się ponieważ do odczytu zastosowano filtr opisany w atrybucie Where.
			// Odczyt konkretnego rekordu na podstawie jego identyfikatora wykonuje metoda readRow().
			if( $this->getWhere()->notEmpty() ){
				$this->LastPDOStatement = $this->DB->prepare($this->queryRead
								.' WHERE '
								.$this->getWhere()->getPrepareStatement()
								.( strlen($this->limit_string) > 0 ? $this->limit_string : NULL)
								);
				$this->LastPDOStatement->execute($this->getWhere()->getPrepareParams()); // Ten zapis nie działał w niektórych przypadkach

//  Powinien byś zastosowany ten zapis ale nie ma możliwości zalogowania się.
//				$this->LastStatement = $this->DB->prepare($this->queryRead.' WHERE '.$this->getWhere()->getPrepareStatement());
//				foreach ($this->getWhere()->getPrepareParams() as $key => $value) {
//					$this->LastStatement->bindParam($key,$value); // @todo wprowadzić kontrolę typów na podstawie danych zgromadzonych w encji - rozbudować encję
//				}
//				$this->LastStatement->execute();

			}else{
				$this->LastPDOStatement = $this->DB->prepare($this->queryRead.' '.( strlen($this->limit_string) > 0 ? $this->limit_string : NULL));
				$this->LastPDOStatement->execute();
			}
			while ($rec = $this->LastPDOStatement->fetch(\PDO::FETCH_ASSOC)){
				$this->rows[$rec['id']] = $rec;
			}
			$this->LastPDOStatement = $this->DB->query('SELECT FOUND_ROWS()');
			$this->count_filtered = (int)$this->LastPDOStatement->fetchColumn();
		}catch(\Exception $E){
			$this->catchAllTypeException($E);
		}
		return $this;
  }
  /**
   * Wczytuje z bazy danych jeden rekord o podanyn identyfikatorze.
   * @param int $id - identyfikator rekordu który ma być odczytany
	 * @return Table
	 * @done 2014-10-14 Wprowadzenie preparowanych zapytań PDO
   */
  protected function readRow($id){
		try{
			$this->LastPDOStatement = $this->DB->query($this->queryRead." WHERE id= '".(int)$id."'");
			if ( ($rec = $this->LastPDOStatement->fetch(\PDO::FETCH_ASSOC)) ){
				$this->rows[$rec['id']] = $rec;
				// @todo Wśród wierszy z bazy danych pojaiwł się nowy rekord. Przemyśleć czy nie będzie konieczności przeładowywania rabeli $this->records[$id]
			}else{
				// @todo Czy w tym miejscu nie wczytanie rekordu o narzuconym identyfikatorze należy unzać za błąd?
			}
		}  catch (\Exception $E){
			$this->catchAllTypeException($E);
		}
		return $this;
  }
  /**
   * Aktualizuje rekordy w bazie danych oznaczonych do aktualizacji na podstawie operacji wykonanych po załądowniu obecnego zestawu rekordów
	 * @return Table
	 * @task 2014-10-14 Wprowadzenie preparowanych zapytań PDO
   */
  protected function update(){
		try{
			$this->LastPDOStatement = $this->DB->prepare($this->queryUpdate);
			foreach ($this->updates as $key => $id) {
				$this->LastPDOStatement->execute($this->DI->prepareParamsUpdate($this->updates[$id]));
				$this->readRow($id);
				unset($this->updates[$id]);
				$this->getRecord($id);
			}
		}  catch (Exception $E){
			$this->catchAllTypeException($E);
		}
		return $this;
  }
	/**
	 * @param \pjpl\db\a\DependenceTableRecord $DI
	 * @done 2014-10-17 Wprowadzenie preparowanych zapytań PDO
	 */
	protected function setDI($DI){
		$DI->setTable($this);
		$this->DI = $DI;
		$this->queryCreate = $this->DI->prepareQueryCreate();
		$this->queryRead = $this->DI->prepareQueryRead();
		$this->queryUpdate = $this->DI->prepareQueryUpdate();
		$this->queryDelete = $this->DI->prepareQueryDelete();
	}
	//------------------------------------------------------------------------------
  // private
  //
	/**
	 * Rozpoznaje wyjątki rzucone w klasie. Analizuje je w celu rzutowania wyjątku specjalizowanego względem numeru błędu.
	 *
	 * Wywołuje metody własnej obsługi wyjątku. Obsłużony wyjątek jest ponownie rzucany na potrzeby kodu wykonującego ten obiekt.
	 *
	 * @param \Exception $E
	 * @throws \Exception
	 * @done 2014-10-30 Przepudowa obsługi wyjątków
	 * @done 2014-10-21
	 */
  final protected function catchAllTypeException(\Exception $E){
		if ( is_a($E, 'E') ) {
			$this->catchE($E);
		} else {
			switch( get_class($E) ){
				case 'PDOException':
					$this->catchPDOException($E);
					break;
				case 'Exception':
					$this->catchException($E);
					break;
				default:
					$this->catchE($E);
			}
		}
	}
	/**
	 * @param \PDOException $PDOException
	 * @done 2014-10-30 Przepudowa obsługi wyjątków
	 */
	final protected function catchPDOException(\PDOException $PDOException){
		$err = $this->LastPDOStatement->errorInfo();
		switch($err[1]){
			case 1451:
				// @todo 2014-10-28 Przeanalizować treść wyjątku PDOException w celu doprecyzowania konstrukcji wyjątku ForeignKey
				$ForeginKey = new \pjpl\e\db\ForeignKey(__CLASS__, __METHOD__, $this->tableName(),'' ,'' );
				$this->catchFreignKey($ForeginKey);
				throw $ForeginKey;
			case 1062:
				// @todo 2014-10-28 Przeanalizować treść wyjątku PDOException w celu doprecyzowania konstrukcji wyjątku NotUnique
				$NotUnique =  new \pjpl\e\db\NotUnique(__CLASS__, __METHOD__, $this->tableName(), '', '');
				$this->catchNotUnique($NotUnique);
				throw $NotUnique;
				// @todo 2014-10-28 Przeanalizować treść wyjątku PDOException w celu doprecyzowania konstrukcji wyjątku General
				$DBGeneral = new \pjpl\e\db\General(__CLASS__, __FUNCTION__);
//				echo '<pre>'.__FILE__.'::'.__LINE__.'<br>'.PHP_EOL.print_r($this->tableName(),TRUE).'</pre>'.PHP_EOL;
//				echo '<pre>'.__FILE__.'::'.__LINE__.'<br>'.PHP_EOL.print_r($err,TRUE).'</pre>'.PHP_EOL;
//				echo '<pre>'.__FILE__.'::'.__LINE__.'<br>'.PHP_EOL.print_r($PDOException->getTrace(),TRUE).'</pre>'.PHP_EOL;
				$this->catchPDOOther($PDOException);
				throw $DBGeneral;
		}
  }
	/**
	 * @param \pjpl\e\a\E $E
	 * @throws \pjpl\e\a\E
	 * @done 2014-10-30 Przepudowa obsługi wyjątków
	 */
	protected function catchE(\pjpl\e\a\E $E){
		switch ( get_class($E) ){
			default:
				throw $E;
		}
	}
	/**
	 * @param \Exception $E
	 * @throws \Exception
	 * @done 2014-10-30 Przepudowa obsługi wyjątków
	 */
	protected function catchException(\Exception $E){
		switch ($E->getCode()){
			default:
				throw $E;
		}
	}
	/**
	 * @param \pjpl\e\db\ForeignKey $ForeignKey
	 * @done 2014-10-30 Przepudowa obsługi wyjątków
	 */
	protected function catchFreignKey(\pjpl\e\db\ForeignKey $ForeignKey){
	}
	/**
	 * @param \pjpl\e\db\NotUnique $NotUnique
	 * @done 2014-10-30 Przepudowa obsługi wyjątków
	 */
	protected function catchNotUnique(\pjpl\e\db\NotUnique $NotUnique){
	}
	/**
	 * @param \PDOException $E
	 * @done 2014-10-30 Przepudowa obsługi wyjątków
	 */
	protected function catchPDOOther(\PDOException $E){
	}
  //------------------------------------------------------------------------------
  // params
  //

  /**
   * @var DB
   */
  protected $DB = null;
  /**
   * Zależność dla utworzonego obiektu klasy
	 * @var \pjpl\db\a\DependenceTableRecord
   */
  protected $DI = null;
	/**
   * Obowiązyjace zapytanie dla odczytu z bazy
   * @var string
   */
  protected $queryRead = null;
	/**
   * Obowiązujące zapytanie dla wstawiania do bazy
   * @var string
   */
  protected $queryCreate = null;
  /**
   * Obowiązujące zapytanie dla aktualizacji bazy
   * @var string
   */
  protected $queryUpdate = null;
	/**
   * Obowiązujące zapytanie dla kasowania
   * @var string
   */
  protected $queryDelete = null;
	/**
	 * Numer rekordu odpowidzi który ma być zwrócony jako pierwszy w tabeli rekordów
	 * @var int
	 */
	protected $start = null;
	/**
	 * Ilość
	 * @var int
	 */
	protected $limit = null;
	/**
	 * Klauzula LIMIT skomponowana ze $start i $limit
	 * @var string
	 */
	protected $limit_string = '';
	/**
	 * Obiekt opisujący warunek Where zapytania sql. Jeżeli jest ustawiony to każde zapytanie będzie tworzone na jego podstawie.
	 * @var Where
	 */
	protected $Where;
	/**
	 * Ostatnio używany statement. Zasięg potrzebny w całej klasie ze względu na obsługę wyjątków które odbywają się w innej metodzie niż zainicjowano $Stmt
	 * @var \PDOStatement
	 */
	protected $LastPDOStatement;


  protected $rows = array();
  /**
   * Identyfikatory rekorów z listy $rows które od czasu wczytania uległy zmianie w bazie danych
   * @var array()
   */
  protected $fresh = array();
  /**
   * Tablica wskaźników do obietków obsługujących rekordy.
   * Kluczem jest identyfikator rekordy wartością obiekt go obsługujący.
   * @var Record
   */
  protected $records = array();
  /**
   * Tablala obiektów opisujących nowe rekordy nie dodane jeszcze do bazy
   * @var Data
   */
  protected $newEncja = array();
  /**
   * tablica rekordów wymagających aktualizacji
   * Przechowuje identyfikatory rekordów które musza być zapisane.
   * @var array()
   */
  protected $updates = array();
  /**
   * Tablica identyfikatorów rekordów przeznaczonych do skasowania.
   * @var array()
   */
  protected $delete = array();
  /**
   * Wynik ostatniego zapytania sql
   * @var object
   */
  protected $result = null;
	/**
	 * Ilość rekordów które zwróciło by zapytanie gdyby nie było ograniczone klauzulą limit
	 * @var BigInt
	 */
	private $count_filtered = 0;



	/**
   * Tabela nie udanych wstawień do bazy.
   * Wartości tej tabeli wskazują na obiekt Data którego nie udało się wstawić
   * @var array[Data]
   */
  //protected $err_create = array();
  /**
   * Tabela błędów zgłoszonych podczas czytania rekordów.
   * Klucze tabeli wskazują na rekord któgo odczyt spowodował błąd.
   * Wartości tabeli wskazują na wyjątek utworzony z powodu błędu (wyjątek nie został rzucony)
   * @var array[int][EDB]
   */
  //protected $err_read = array();
  /**
   * Tabela błędów zgłoszonych podczas aktualizacji rekordów.
   * Klucze tabeli wskazują na rekord któgo odczyt spowodował błąd.
   * Wartości tabeli wskazują na wyjątek utworzony z powodu błędu (wyjątek nie został rzucony)
   * @var array[int][EDB]
   */
  //protected $err_update = array();
  /**
   * Tabela błędów zgłoszonych podczas kasowania rekordów.
   * Klucze tabeli wskazują na rekord, któgo odczyt spowodował błąd.
   * Wartości tabeli wskazują na wyjątek utworzony z powodu błędu (wyjątek nie został rzucony)
   * @var array[int][EDB]
   */
  //protected $err_delete = array();

	/**
   * Kod błędy wygenrowany podaczas istatniego zapytania
   * @var int
   */
  //protected $errno = 0;
  /**
   * Opis błędu wygenerowanego podczas ostatniego zapytania;
   * @var string
   */
  //protected $errst = '';
  /**
   * Wyjątek utworzony do obługi błędu wygenerowanego podczas ostatniego zapytania
   * @var array[\pjpl\depreciate\E]
   */
  //protected $E = array();
  /**
   * Rekordy wczytane po wykonanych zapytaniach.
   * Kluczem jest identyfikator rekordu - wartością czały rekord
   * @var array()
   */



  static protected $instance = null;

}
