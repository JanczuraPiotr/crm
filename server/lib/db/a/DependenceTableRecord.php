<?php
namespace crmsw\lib\db\a;
use crmsw\lib\db\Record;
use pjpl\db\a\Encja;

/**
 * @confirm 2014-12-19
 * @doc 2014-12-19
 */
abstract class DependenceTableRecord extends \pjpl\db\a\DependenceTableRecord{
  abstract public function tabelaId();
	/**
	 * Tworzy record z wiersza danych którego referencję podano jako parametr
	 * @param array $row
	 * @return Record
	 */
	public function fromRowToRecord(&$row){
		return new Record($row['id'], $row['create'], $row['update'], $this->fromRowToEncja($row), $this->Table);
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
			$prepare .= $przecinek." `$key` = :$key";
			$przecinek = ",";
		}
		$prepare .= ", `update` = :update";
	  return "UPDATE ".$this->tableName()." SET ".$prepare." WHERE id = :id";
	}
	/**
	 * @param Record $Record
	 * @param int $µs
	 * @return type
	 */
	public function prepareParamsUpdate(Record $Record, $µs){
		$prepare = [];
		foreach ($Record->toArray() as $key => $value) {
			$prepare[':'.$key] = $value;
		}
		$prepare[':update'] = $µs;
		return $prepare;
	}
}
