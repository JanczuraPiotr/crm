<?php
namespace pjpl\e;
use pjpl\db\Where;
/**
 * Nie znaleziono encji. Wyjątek powinien być stosowany dylko w sytuacji gdy z kontekstu wynika że encja powinna istnieć.
 * @package pjpl
 * @subpackage exceptions
 * @author Piotr Janczura <piotr@janczura.pl>
 * @done 2014-09-17
 */
class NoEncja extends \pjpl\e\a\E{
	/**
	 * Zbiór który był przeszukany w poszukioaniu encji.
	 * @todo collection to w tej chwili nazwa przeszukiwanego zbioru w postaci napisu. Może uda się z tego zrobić wskaźnik do obiektu zarządzającego zbiorem
	 * @var string
	 */
	private $collection;
	/**
	 * Obiekt opisujący zapytanie dla którego nie znaleziono spodziewanej encji
	 * @var Where
	 */
	private $Where;
	/**
	 * @param string $class_name Nazwa klasy w której stwierdzono
	 * @param string $function_name
	 * @param string $collection
	 * @param Where $where
	 */
	public function __construct($class_name, $function_name, $collection, $Where) {
		$this->collection = $collection;
		$this->Where = $where;
		parent::__construct($class_name, $function_name, 'Nie znaleziono encji w zbiorze : '.$this->collection.' na podstawie filtra : '.$this->Where->getString());
	}
	/**
	 * Nazwa przeszukiwanego zbioru.
	 * @return string
	 */
	public function getCollection(){
		return $this->collection;
	}
	/**
	 * Obiekt opisujący zapytanie które dało wynik negatywny
	 * @return Where
	 */
	public function getWhere(){
		return $this->Where;
	}

	public function code() {
		return self::ENOENCJA;
	}

	public function name() {
		return 'ERR_ENOENCJA';
	}

}