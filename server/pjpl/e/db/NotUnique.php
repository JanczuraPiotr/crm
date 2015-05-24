<?php
namespace pjpl\e\db;
/**
 * Próba zaburzenia unikalności wartości kolumny.
 *
 * @package pjpl
 * @subpackage exceptions
 * @author Piotr Janczura <piotr@janczura.pl>
 * @confirm 2014-10-28
 */
class NotUnique extends \pjpl\e\a\E {
	/**
	 * @var string
	 */
	private $table;
	/**
	 * @var string
	 */
	private $column;
	/**
	 * @var mixed
	 */
	private $value;
	/**
	 * @param string $class_name - Nazwa klasy w której zgłoszono wyjątek, (przeważnie wnętrze obiektu $encja)
	 * @param string $function_name - funkcja w której zgłoszono wyjątek.
	 * @param string $table - Nazwa tabeli do której odwołano się powodując wyjątek.
	 * @param string $column - Nazwa kolumny w tabeli do której odwołano się powodując wyjątek.
	 * @param mixed $value - Wartość którą próbowano nadać kolumnie $table.$column powodując wyjątek.
	 */
	public function __construct($class_name, $function_name, $table, $column, $value) {
		parent::__construct($class_name, $function_name, "Modyfikując kolumnę : $column w tabeli : $table wartością : $value zaburzono unikalność wartości w kolumnie");
		$this->table = $table;
		$this->column = $column;
		$this->value = $value;
	}
	/**
	 * Nazwa tabeli do której odwołano się powodując wyjątek.
	 * @return string
	 */
	public function getTable(){
		return $this->table;
	}
	/**
	 * Nazwa kolumny w tabeli do której odwołano się powodując wyjątek.
	 * @return string
	 */
	public function getColumn(){
		return $this->column;
	}
	/**
	 * Wartość którą próbowano nadać kolumnie $table.$column powodując wyjątek.
	 * @return mixed
	 */
	public function getValue(){
		return $this->value;
	}
	public function code() {
		return self::EDB_NOTUNIQUE;
	}
	public function name() {
		return 'ERR_EDB_NOTUNIQUE';
	}
}