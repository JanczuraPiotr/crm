<?php
namespace pjpl\db;
use pjpl\db\a\Encja;
use pjpl\db\a\Table;
/**
 * Encja wraz z identyfikatorem rekordu w którym Encja jest zapisana w bazie danych oraz dodatkowe metody wsółpracy z tabelą bazy danych.
 *
 * Nie zawiera ewentualnych informacji o czasie utworzenia i aktualizacji rekordu
 * Metoda __get() została napisana tak by można było pobrać
 *	- id identyfikator rekordu w którym zapiana jest encja
 *	- Table referencję do obiektu obsługującego tabelę bazy danych w której zapisany jest rekord
 *	- Encja referncję do encji
 *	- attributes kopię tablicy atrybutów
 * Przykładowe użycie:
 * <code>
 *	$tOsoby; //tabela bazy danych przechowująca informacje o osobach
 *	$eOsoba = Osoba::create([
 *		'nazwisko' => 'Kowalski',
 *		'imie' => 'Jan'
 *	]);
 *	$nazwisko = $eOsoba->nazwisko; // Kowalski
 *	$rOsoba = $tOsoby->createRecordImmediately($eOsoba);
 *	$rOsoba->Encja === $eOsoba;
 *	$rOsoba->attributes === $eOsoba->toArray();
 *
 * </code>
 * @see Encja
 * @confirm 2014-09-11
 * @doc 2014-10-14
 */
class Record{
	/**
	 * @var int
	 */
  protected $id = null;
  protected $Table = null;
	/**
	 * @var Encja
	 */
  protected $Encja = null;
  /**
   *
   * @param int $id
   * @param Encja $Encja
   * @param Table $Table
   */
  public function __construct($id, Encja $Encja, $Table) {
    $this->id = (int)$id;
    $this->Encja = $Encja;
    $this->Table = $Table;
  }
	/**
	 * @return int
	 */
  public function getId(){
    return $this->id;
  }
	/**
	 * @return Encja
	 */
  public function getEncja(){
    return $this->Encja;
  }

	public function toArray(){
		return array_merge(['id' => $this->id] , $this->Encja->toArray() );
	}
  /**
   * Wstawia rekord to tabeli rekordów przeznaczonych do aktualizacji.
   * Aktualizacja odbywa się w zaplanowanym czasie lub wewnątrz destruktora tabeli obsługującej bazę danych do której należy ten rekord.
   */
  public function updateShedule(){
    $this->Table->updateRecord($this);
  }
  /**
   * Natychmiast aktualizuje rekord.
   * Rekord nie jest wstawiony do tabeli rekordów przeznaczonych do aktualizacji
   */
  public function updateImmediately(){
    $this->Table->updateRecordImmediately($this);
  }
	public function __get($attribute){
		switch($attribute){
			case 'id':
				return $this->id;
			case 'Encja':
			case 'encja':
				return $this->Encja;
			case 'Table':
				return $this->Table;
			default :
				return $this->Encja->{$attribute};
		}
	}
	public function __set($attribute, $value) {
		switch ($attribute){
			case 'id':
				$this->id = (int)$value;
				break;
			case 'Table':
			case 'table':
				$this->Table = $value;
				break;
			default:
				$this->Encja->{$attribute} = $value;
		}
	}
}