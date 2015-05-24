<?php
namespace pjpl\e;
/**
 * Nie znaleziono rekordu na podstawie wartości identyfikatora rekordu
 * @package pjpl
 * @subpackage exceptions
 * @author Piotr Janczura <piotr@janczura.pl>
 * @confirm 2014-09-08
 */
class NoRecord extends \pjpl\e\a\E{
	/**
	 * Nazwa pszeszukiwanej tabeli
	 * @var string
	 */
	private $table;
	/**
	 * Wartość identyfikatora dla którego szukany był rekord
	 * @var int
	 */
	private $id;
	/**
	 * @param string $class_name - Nazwa klasy w której zgłoszono wyjątek
	 * @param string $function_name - Nazwa metody w której zgłoszono wyjątek
	 * @param string $table - tabla bazy danych w której szukano rekordu o identyfikatorze $id
	 * @param int $id - identyfikator szukanego rekordu
	 */
	public function __construct($class_name, $function_name, $table, $id) {
		$this->table = $table;
		$this->id = $id;
		parent::__construct($class_name, $function_name, "Nie znaleziono rekordu id = $this->id w tabeli : $this->table");
	}
	/**
	 * Nazwa pszeszukiwanej tabeli
	 * @return string
	 */
	public function getTable(){
		return $this->table;
	}
	/**
	 * Wartość identyfikatora dla którego szukany był rekord
	 * @return int
	 */
	public function getId(){
		return $this->id;
	}

	public function code() {
		return self::ENORECORD;
	}

	public function name() {
		return 'ERR_ENORECORD';
	}

}