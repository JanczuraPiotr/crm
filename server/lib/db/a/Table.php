<?php
namespace crmsw\lib\db\a;
use pjpl\db\a\DependenceTableRecord;
use pjpl\db\Record;
/**
 * @confirm 2014-12-19
 * @prace 2014-12-18 Do tabel dodać kolumnę delete i ustawiać ja czasem µs kasowania rekordu. Skasowanie rekordu będzie odbywało się poprzez ustawienie tej kolumny
 * @prace 2014-12-18 Utworzyć metodę clean() która będzie usuwać z tabeli rekordy oznaczone za skasowane
 */
abstract class Table extends \pjpl\db\a\Table{

	/**
	 * Metoda na potrzeby klasy CRM_Record !! używać za pośrednictwem obiektu klasy CRM_Record::getCreate()
	 */
	public function getCreate($id){
		return $this->rows[$id]['create'];
	}
	/**
	 * Metoda na potrzeby klasy CRM_Record !! używać za pośrednictwem obiektu klasy CRM_Record::getUpdate()
	 */
	public function getUpdate($id){
		return $this->rows[$id]['update'];
	}
	/**
	 * Metoda kontroluje czy zalogowany użytkownik ma prawo do utworzenia rekordu oraz wywołuje metodę DB::doneCreate()
	 * w celu poinformowania o czasie modyfikacji tabeli.
   * @throws pjpl\depreciate\InsertRight
   */
  public function create() {
    if($this->DB->canCreate($this->DI->TabelaId())){
      parent::create();
      $this->µsCreate = µs();
      $this->DB->doneCreating($this->DI->TabelaId(),  $this->µsCreate);
    }else{
      $E = new pjpl\depreciate\InsertRight(__CLASS__, __FUNCTION__, $this->queryCreate, 'Użytkownik nie ma praw tworzenia rekordów w tabeli : ',  $this->DI->tableName());
      throw $E;
    }
  }
  /**
	 * Metoda kontroluje czy zalogowany użytkownik ma prawo modyfikować rekord oraz wywołuje metodę DB::doneUpdating()
	 * w celu poinformowanie o czasie modyfikacji tabeli
   * @throws pjpl\depreciate\UpdateRight
   */
  protected function update() {
    if($this->DB->canUpdate($this->DI->TabelaId())){
			$this->LastPDOStatement = $this->DB->prepare($this->queryUpdate);
			foreach ($this->updates as $key => $id) {
				$this->µsUpdate = µs();
				$this->LastPDOStatement->execute($this->DI->prepareParamsUpdate($this->updates[$id], $this->µsUpdate));
				$this->DB->doneUpdating($this->DI->TabelaId(),  $this->µsUpdate);
				$this->readRow($id);
				unset($this->updates[$id]);
				$this->getRecord($id);
			}
    }else{
      $E = new pjpl\depreciate\UpdateRight(__CLASS__, __FUNCTION__, $this->queryUpdate, 'Użytkownik nie ma praw aktualizacji w tabeli : '.$this->DI->tableName());
      throw $E;
    }
  }
	/**
	 * @param Record $Record
	 * @return Table
	 * @prace 2014-10-14 Wprowadzenie preparowanych zapytań PDO
	 */
  public function updateRecordImmediately(Record $Record){
    if($this->DB->canUpdate($this->DI->TabelaId())){
		 	$this->µsUpdate = µs();
			$this->LastPDOStatement = $this->DB->prepare($this->queryUpdate);
			$this->LastPDOStatement->execute($this->DI->prepareParamsUpdate($Record, $this->µsUpdate));
      $this->DB->doneUpdating($this->DI->TabelaId(), $this->µsUpdate);
			$this->readRow($Record->getId());
		}else{
      $E = new pjpl\depreciate\UpdateRight(__CLASS__, __FUNCTION__, $this->queryUpdate, 'Użytkownik nie ma praw aktualizacji w tabeli : '.$this->DI->tableName());
      throw $E;
    }
		return $this;
  }

  public function updateRecordsArrayImmediately(array $Records){
    if($this->DB->canUpdate($this->DI->TabelaId())){
			$this->µsUpdate = µs();
      parent::updateRecordsArrayImmediately($Records);
      $this->DB->doneUpdating($this->DI->TabelaId(),  $this->µsUpdate);
    }else{
      $E = new pjpl\depreciate\UpdateRight(__CLASS__, __FUNCTION__, $this->queryUpdate, 'Użytkownik nie ma praw aktualizacji w tabeli : '.$this->DI->tableName());
      throw $E;
    }
  }

  /**
	 * Metoda kontroluje czy zalogowany użytkownik ma prawo usunąć rekord oraz wywołuje metodę DB::doneDeleting()
	 * w celu poinformowanie o czasie usunięcia rekordu z tabeli
   * @throws pjpl\depreciate\DeleteRight
   */
  protected function delete() {

    if($this->DB->canDelete($this->DI->TabelaId())){
      parent::delete();
      $this->µsDelete = µs();
      $this->DB->doneDeleting($this->DI->TabelaId(),  $this->µsDelete);
    }  else {
      $E = new pjpl\depreciate\DeleteRight(__CLASS__, __FUNCTION__, $this->DI->tableName(), '', '', '', 'Użytkownik nie ma praw usuwania z tabeli : '.$this->DI->tableName());
      throw $E;
    }
  }
  protected $µsCreate = 0;
	protected $µsUpdate = 0;
  protected $µsDelete = 0;
  /**
   * Zależność dla utworzonego obiektu klasy
	 * @var \crmsw\lib\db\a\DependenceTableRecord
   */
  protected $DI = null;

}