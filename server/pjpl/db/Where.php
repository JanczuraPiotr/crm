<?php
namespace pjpl\db;
use pjpl\i\NeverNull;
/**
 * Wyrażenie wstawiane w klauzulę WHERE zapytania sql.
 *
 * @package pjpl
 * @subpackage database
 * @author Piotr Janczura <piotr@janczura.pl>
 * @confirm 2014-10-20 Wprowadzenie preparowanych zapytań PDO
 * @confirm 2014-09-26 Filtr może składać się tylko z wyrażeń : ['attribute' => 'zmienna_lub_koluma_bazy', 'operator' => 'jeden z : <, >, =, like', 'value' => 'szukana_wartosc'];
 * @todo Nie obsługuje zapisu typu zamienn = null albo zmienna != null
 * @doc 2014-10-14
 */
class Where implements NeverNull{ // @todo ten interfejs nie spełnia tu założeń
	/**
	 * @var array
	 */
	private $expressions = array();
	/**
	 * Wstawia do klauzuli where warunek logiczny.
	 *
	 * Przykładowe sposoby konstrukcji:
	 * <code>
	 *	$filter = [];
	 *	$filter[] = ['attribute' => 'miejscowosc', 'operator'=> '=', 'value' => 'Katowice'];
	 *	$filter[] = ['attribute' => 'dochod', 'operator'=> '>', 'value' => '1000'];
	 *	$filter[] = ['attribute' => 'dochod', 'operator'=> '<', 'value' => '3000'];
	 *	$Where = new Where($filter);
	 *	echo $Where->getString();
	 *  // zwróci : `nazwisko` like 'ko' and  `miejscowosc` = 'Katowice' and  `dochod` > '1000' and  `dochod` < '3000'
	 * </code>
	 * @todo obsłużyć sytuację is_null(atrybut)
	 */
	final public function __construct(array $filter = array()) {
		if($filter && is_array($filter)){
			foreach ($filter as $key => $value) {
				$this->append($value['attribute'], $value['operator'], $value['value']);
			}
		}
	}
	/**
	 * Wstawia do klauzuli where kolejny warunek logiczny.
	 *
	 * Niezależnie czy obiekt powstał na podstawie tablicy definiującej filtr czy konstruktor wywołano bez parametru można dodawać do niego kolejne warunki.
	 * <code>
	 *  $where->append('auto','like', 'fia');
	 *  $where->append('telefon','like', 'no');
	 *	echo $Where->getString();
	 *	daje : `nazwisko` like 'ko' and  `miejscowosc` = 'Katowice' and  `dochod` > '1000' and  `dochod` < '3000' and  `auto` like 'fia' and  `telefon` like 'no'
	 * </code>
	 * @param string $attributes - np nazwa kolumny w tabli bazy danych lub nazwa attryburtu w encji do której za pomocą operatora porównania porównywane są $...operator
	 * @param string $operator - operator stawiany między $attrybut a $value
	 * @param string $value operant stojący po prawej stronie attrybutu w takich przypadkach jak attrybut <= 5 lub attrybut != 5
	 * @return Where
	 * @confirm 2014-10-20 Wprowadzenie preparowanych zapytań PDO
	 */
	public function append($attribute, $operator, $value){
		switch ($operator = strtoupper($operator)){
			case 'LIKE':
				$tmp = [];
				$tmp['attribute'] = strtolower($attribute);
				$tmp['operator'] = $operator;
				$tmp['value'] = strtolower($value);
				$this->expressions[] = $tmp;
				break;
			default :
				$this->expressions[] = [
								'attribute' => $attribute,
								'operator'  => $operator,
								'value'     => $value
				];
		}
		return $this;
	}
	/**
	 * Zwraca string warunku WHERE spreparowanego zgodnie z zasadami PDO
	 * @return string
	 * @confirm 2014-10-20 Wprowadzenie preparowanych zapytań PDO
	 */
	public function getPrepareStatement(){
		if(count($this->expressions) === 0){
			return '';
		}
		$where = '';
		foreach ($this->expressions as $key => $expression) {
			$this->mergeStatement($where, $expression);
		}
		return $where;
	}
	/**
	 * Zwraca tablicę preparowanych, zmiennych zapytania i ich wartości. Preparowanie odbywa się zgodnie z zasadami PDO
	 * @return array
	 * @confirm 2014-10-20 Wprowadzenie preparowanych zapytań PDO
	 */
	public function getPrepareParams(){
		$array = [];
		foreach ($this->expressions as $key => $expression) {
			switch ($expression['operator']){
				case 'LIKE':
					if(strtoupper($expression['value']) === 'NULL' || $expression['value'] === NULL){
					}else{
						$array[':'.$expression['attribute']] = $expression['value']."%";
					}
					break;
				default:
					if(strtoupper($expression['value']) === 'NULL' || $expression['value'] === NULL){
					}else{
						$array[':'.$expression['attribute']] = $expression['value'];
					}
			}
		}
		return $array;
	}
	/**
	 * Obraz fragmentu zapytania generowanego przez obiekt.
	 * pjpl nie przewiduje stosowania tej metody w tabelach. Metoda istnieje dla ułatwienia testowania poprawności warunku podczas debugowania.
	 * @return string
	 * @confirm 2014-10-14 Wprowadzenie preparowanych zapytań PDO
	 */
	public function getString(){
		if(count($this->expressions) === 0){
			return '';
		}
		$where = '';
		foreach ($this->expressions as $key => $expression) {
			$this->mergeString($where, $expression);
		}
		return $where;
	}
	/**
	 * Zwraca kopię tablicy opisującą całe zapytanie.
	 * @return array
	 */
	public function getArray(){
		return $this->expressions;
	}
	/**
	 *
	 * @param type $where
	 * @param type $expression
	 * @return \pjpl\db\Where
	 * @confirm 2014-10-20 Wprowadzenie preparowanych zapytań PDO
	 */
	protected function mergeStatement(& $where, & $expression){
		if(strlen($where) > 0){
			$where .= ' AND '.$this->conditionStatement($expression);
		}else{
			$where = $this->conditionStatement($expression);
		}
		return $this;
	}
	/**
	 * Realizuje złączenia warunku z wskazanego wyrażenia do już utworzonej części klauzuli where
	 * @param string $where dotychczas utworzona część klauzuli where
	 * @param string $expression tablica opisująca kolejne podzapytanie które ma być doklejone do $where
	 * @return Where
	 * @confirm 2014-10-20 Wprowadzenie preparowanych zapytań PDO
	 */
	protected function mergeString(& $where, & $expression){
		if(strlen($where) > 0){
			$where .= ' AND '.$this->conditionString($expression);
		}else{
			$where = $this->conditionString($expression);
		}
		return $this;
	}
	/**
	 * Przetwarza na string jeden wiersz tablicy $expressions
	 * @param array $expression wygląda tak [$attrybut,$operator,$value]
	 * @return string jedna składowa warunku np : `miejscowosc` = :miejscowosc' lub `miejscowosc` like :miejscowosc%
	 * @confirm 2014-10-20 Wprowadzenie preparowanych zapytań PDO
	 */
	protected function conditionStatement(& $expression){
		$string = '';
//		switch ($expression['operator']){
//			case 'LIKE':
//				$string = 'lcase('.$expression['attribute'].') LIKE :'.$expression['attribute'];
//				break;
//			default:
//				$string = $expression['attribute'].' '.$expression['operator'].' :'.$expression['attribute'];
//		}
//		$string = $expression['attribute'].' '.$expression['operator'].' \':'.$expression['attribute'].'\'';
		if(strtoupper($expression['value']) === 'NULL' || $expression['value'] === NULL){
			$string = 'isnull('.$expression['attribute'].')';
		}else{
			$string = $expression['attribute'].' '.$expression['operator'].' :'.$expression['attribute'];
		}
		return $string;
	}
	/**
	 * Przetwarza na string jeden wiersz tablicy $expressions
	 * @param array $expression wygląda tak [$attrybut,$operator,$value]
	 * @return string jedna składowa warunku np : `miejscowosc` = 'Katowice' lub `miejscowosc` like 'Katow%'
	 * @confirm 2014-10-20 Wprowadzenie preparowanych zapytań PDO
	 */
	protected function conditionString(& $expression){
		$string = '';
		switch ($expression['operator']){
			case 'LIKE':
				$string = 'lcase('.$expression['attribute'].') LIKE '.$expression['value'].'%';
				break;
			default :
				$string = '`'.$expression['attribute'].'` '.$expression['operator'].' \''.$expression['value'].'\'';
		}
		return $string;
	}

	public function isEmpty() {
		if( count($this->expressions) < 1 ){
			return true;
		}else{
			return false;
		}
	}

	public function notEmpty() {
		if( count($this->expressions) > 0 ){
			return true;
		}else{
			return false;
		}
	}

	public function setEpmty() {
		$this->expressions = [];
	}

}
