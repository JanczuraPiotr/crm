<?php
namespace pjpl\db\a;
use pjpl\e\NoExistAttribute;
/**
 * Niepodzielny zestaw danych wspólnie opisujących złożony obiekt.
 *
 * Ponieważ klasa przeznaczona jest do opisu obiektów umieszczonych w tabeli bazy danych, w klasie dziedziczącej należy utworzyć
 * parametry o nazwach odpowiadających nazwom kolumn w tabeli w której są umieszczone. Prócz parametrów utworzyć należy metody get i set
 * operujące na parametrach. Metody muszą zapewniać kontrolę nad poprawnością pojedynczych pól oraz ich koherentność.
 *
 * @package pjpl
 * @subpackage database
 * @author Piotr Janczura <piotr@janczura.pl>
 * @todo Rozbudowa o metodę zwracającą tablicę opisującą typy atrybutów i dopuszczalnych a potem o kontrolę czY encja spełnia warunki
 * @todo Opracować sposób testowania poprawności danych przed zapisem do bazy - Na razie błąd w danych stwierdzany jest przez odrzucenie encji przez bazę
 */
abstract class Encja {
	/**
	 * Utworzy rekord inicjując $this->row domyślnymi wartościami parametrów
	 */
	const CREATE_NULL = 1;
	const CREATE_DEFAULT = 2;
	const CREATE_DYNAMICALY = 3;

	protected $attributes;

	/**
	 * Tworząc obiekt domyślnymi wartościami konstruktora otrzymasz obiekt znający zakres przechowujących przez siebie danych zainicjowane wartościami null.
	 *
	 * Przekazując tablicę asocjacyjną zmiennych i ich wartości możesz zainicjować wybrane pola, podając wszystkie zmienne zainicjujesz cały obiekt.
	 * Podając w tablicy $attributes nazwę zmiennej nie należącą do zestawu danych otrzymasz wyjątek.
	 *
	 * @param array $attributes
	 * @param int $create_typ
	 * @throws NoExistAttribute - gdy tablica $we zawiera nazwę zmiennej nie zawierającej się w tej Encji
	 * @todo konstruktor powinien być finalny i chroniony
	 */
	protected function __construct($attributes = array(), $create_typ = Encja::CREATE_NULL){
		switch ($create_typ){
			case Encja::CREATE_NULL:
				$this->attributes = static::createNull();
				break;
			case Encja::CREATE_DEFAULT:
				$this->attributes = static::createDefault();
				break;
			case Encja::CREATE_DYNAMICALY:
				$this->attributes = static::createDinamicaly();
				break;
			default :
				// @todo zgłoś tu jakiś wyjątek
		}
		// Inicjowanie tablicy atrybutów napisałem w ten sposób by z tablicy wejściowej nie pobierane były atrybuty które nie stanowią części encji.
		// Tablice będące źródłem atrybutów dla encji mogą zawierać dodatkowe informacje konstruujące rekord bazy danych które nie są częścią encji.
		foreach ($this->attributes as $attribute => $value) {
			if(isset($attributes[$attribute])){
				$this->{$attribute} = $attributes[$attribute];
			}
		}
	}
	/**
	 * Tworzy obiekt a z powodu finalności konstruktora jest jedynym miejscem gdzie obiekty potomne można tworzyć w połączeniu z dodatkowymi czynnościami.
	 * @param array $attributes
	 * @param int $create_typ
	 * @done 2014-09-03
	 * @return \static
	 */
	static public function create($attributes = array(), $create_typ = Encja::CREATE_NULL){
  	return new static($attributes , $create_typ);
  }
	/**
	 * Nadaje wartość $value zmiennej $attribute ...
	 *
	 * W klasach pochodnych powinna być nadpisana tak by każda zmienna która tego wymaga była zapisywana do $this->row za pośrednictwem
	 * rzutowania i ewentualnych testów poprawności.
	 *
	 * @throws NoExistAttribute gdy $attribute jest nazwą zmiennej nie znanej w tej Encji
	 */
	public function __set($attribute, $value){
		if(array_key_exists($attribute, $this->attributes)){
			$this->attributes[$attribute] = $value;
		}else{
			throw new NoExistAttribute(__CLASS__,__FUNCTION__,  $this , $attribute);
		}
	}
	/**
	 * Pobiera wartość zmiennej $attribute ...
	 *
	 * @todo Przetestować wydajność medoty __get w porównaniu do metod specjalizowanych getNazwaAtrybutu
	 * @throws NoExistAttribute gdy $attribute jest nazwą zmiennej nie znanej w tej Encji
	 */
	public function __get($attribute){
		if(array_key_exists($attribute, $this->attributes)){
			return $this->attributes[$attribute];
		}else{
			throw new NoExistAttribute(__CLASS__,__FUNCTION__,  $this , $attribute);
		}
	}
	/**
	 * Utworzył atrybuty zależnie od kontekstu wywołania konstruktora encji
	 */
	protected static function createDinamicaly(){
		return static::nullRow();
	}
	/**
	 * Utworzy zestaw danych obiektu o pustej wartości.
	 * @return array[name][value]
	 */
	protected static function createNull(){
		return static::nullRow();
	}
	/**
	 * Utworzy zestaw danych obiektu o domyślnej wartości
	 * @return array[name][value]
	 */
	protected static function createDefault(){
		return static::defaultRow();
	}
	/**
	 * Zwraca tablicę opisującą domyślną encje.
	 *
	 * Domyślna encja, może być wpisana do bazy danych bez dodatkowej modyfikacji, tworząc mimo to logiczny rekord.
	 * Jej elementy mogą być stałe w całym cyklu życia systemu lub być modyfikowane w chwili wygenerowania.
	 * Pozwala tworzyć szablon dla nadchodzących danych tak by te nie musiały tworzyć kompletnego rekordu, dzięki czemu
	 * rekord może powstać na podstawie domyślnych wartości i dowolnego podzbioru wartości nadesłanych.
	 *
	 * @return array Tablica asocjacyjna nazwa pól obiektu i ich wartości
	 */
	public static function defaultRow(){
		return static::nullRow();
	}
	/**
	 * Zwraca tabele opisującą pustą encje.
	 *
	 * Taka tablica służy jako inicjator tabeli opisujących encję zapewniający, że znajdą się w niej wszystkie wymagane kolumny bazy danych.
	 * Rekord utworzony na podstawie tej tablicy nie zawiera żadnych informacji.
	 * Przygotowana w ten sposób tablica aktualizowana jest wewnątrz logiki biznesowej taką ilością danych jaka została nadesłana
	 * nawet gdy zestaw nadesłanych danych jest tylko podzbiorem zbioru kolumn w bazie. Dzięki czemu po podczas przetwarzania rekordu nie ma konieczności
	 * kontrolowania czy wartość w istnieje a podczas wstawienia do bazy danych rekordu na podstawie tej tabeli nie otrzymujemy komunikatu o błędzie.
	 *
	 * @todo Co gdy występują pola które nie mają wartości null. Tworzyć szablon ale zgłaszać błąd gdy na jego podstawie tworzony jest rekord?
 	 * @return array Tablica asocjacyjna nazw pól obiektu i ich wartości
	 */
	abstract public static function nullRow();
	/**
	 * Tablica z aktualnymi wartościami pól
	 * @return array Tablica assocjacyjna nazw pól obiektu i ich wartości
	 */
	public function toArray(){
		return $this->attributes;
//		$ret = array();
//		foreach ($this->attributes as $attribute => $value) {
//			$ret[$attribute] = $value;
//		}
//		return $ret;
	}
	/**
	 * Inicjowanie obiektu na podstawie przekazanej tablicy pól.
	 * @param array $row tablica pól i ich wartości którymi należy zmodyfikować wartość obiektu
	 * @throws NoExistAttribute gdy $attribute jest nazwą zmiennej nie znanej w tej Encji
	 */
	public function fromArray($row){
		foreach ($row as $attribute => $value) {
			$this->attributes[$attribute] = $value;
		}
	}

}
