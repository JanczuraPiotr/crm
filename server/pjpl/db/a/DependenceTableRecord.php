<?php
namespace pjpl\db\a;
//use crmsw\lib\db\Record;
use pjpl\db\a\Encja;
use pjpl\db\Record;
use pjpl\db\a\Table;
/**
 * Zależności dla danego typy tabeli bazy danych
 * @done 2014-12-19
 * @doc 2014-10-14
 */
abstract class DependenceTableRecord{
	/**
	 * Tworzy nową encję dla obsługiwanej tabeli danych
	 * @param int $create_typ
	 * @return Encja
	 */
	public function createEncja($create_typ = Encja::CREATE_NULL){
		$className = static::className();
		return $className::create($create_typ);
	}
	/**
   * Tworzy encję z wiersza bazy danych którego referencję podano jako parametr
   * @param array $row Wiersz tabeli z którego należy wydobyć dane do utworzenia obiektu.
   * @return Encja
   */
  public function fromRowToEncja(&$row){
		$className = static::className();
		return $className::create($row);
	}
	/**
	 * Tworzy record z wiersza danych którego referencję podano jako parametr
	 * @param array $row
	 * @return Record
	 */
	public function fromRowToRecord(&$row){
		return new Record($row['id'], $this->fromRowToEncja($row), $this->Table);
	}
	/**
	 * @param Encja $Encja
	 * @return type
	 * @doc 2014-10-18
	 */
	public function prepareParamsCreate(Encja $Encja){
		$prepare = [];
		foreach ($Encja->toArray() as $key => $value) {
			$prepare[':'.$key] = $value;
		}
		return $prepare;
	}
	/**
	 * @param Encja $Encja
	 * @return type
	 * @doc 2014-10-18
	 */
	public function prepareParamsUpdate(Record $Record){
		$prepare = [];
		foreach ($Record->toArray() as $key => $value) {
			$prepare[':'.$key] = $value;
		}
	  return "UPDATE ".$this->tableName()." SET ".$prepare." WHERE id = :id";
	}
	/**
	 * Na podstawie konkretnego typu Encji na potrzeby tabeli bazy danych dla której jest definiowana klasa musi utworzyć string
	 * który będzie wykorzystywany jako część preparowanego zapytania wstawiającego rekord np : '(`nazwisko`, `imie`) VALUES (:nazwisko, :imie)'
	 * @return string Napis będący częścią preparowanego zapytania tworzącego nowy rekord
	 */
	public function prepareQueryCreate(){
		$className = static::className();
		$row_name = $className::nullRow();
		$str_name = '';
		$str_value = '';
		$przecinek = '';
		foreach ($row_name as $key => $value) {
			$str_name .= $przecinek." `$key`";
			$str_value .= $przecinek." :$key";
			$przecinek = ",";
		}
		return "INSERT INTO ".$this->tableName()." ($str_name) VALUE ($str_value)";
	}
	/**
	 * Na podstawie konkretnego typu Encji na potrzeby tabeli bazy danych dla której jest definiowana klasa musi utworzyć string
	 * który będzie wykorzystywany jako część zapytania aktualizującego rekord np : '`nazwisko = :nazwisko, `imie` = :imie'
	 * @return string Napis będący częścią preprowanego zapytania aktualizującego tabelę bazy danych
	 */
	public function prepareQueryUpdate(){
		$className = static::className();
		$row_name = $className::nullRow();
		$prepare = "";
		$przecinek = "";
		foreach ($row_name as $key => $value) {
			$prepare .= $przecinek." $key = :$key";
			$przecinek = ",";
		}
	  return "UPDATE ".$this->tableName()." SET ".$prepare." WHERE id = :id";
	}
	/**
	 * @doc 2014-10-18
	 */
	public function prepareQueryRead(){
		return  "SELECT SQL_CALC_FOUND_ROWS * FROM ".$this->tableName();
	}
	/**
	 * @doc 2014-10-18
	 */
  public function prepareQueryDelete(){
    return "DELETE FROM ".$this->tableName()." WHERE id = :id";
  }
	/**
	 * Nazwa tablicy bazy danych dla której utworzono ten obiekt
   * @return string
   */
  abstract public function tableName();
	/**
	 * Nazwa klasy obsługującej pojedynczy rekord
	 * @return string
	 */
	abstract public function className();
	/**
	 * Metoda musi być wywołana w obiekcie klasy Table gdy wstawiany jest do niej obiekt DependencyTableRecord.
	 * @param Table $Table
	 */
	public function setTable(Table $Table){
		$this->Table = $Table;
	}

	/**
	 * Referencja na obiekt obsługujący tabelę bazy danych do którego wstrzyknięto ten obiekt
	 * @var Table
	 */
	protected $Table;
}