<?php
namespace pjpl\e;
use pjpl\e\a\E;
/**
 * Próba odwołania do nieistniejącego atrybutu Encji
 * @confirm 2014-09-17
 */
class NoExistAttribute extends E{
	/**
	 * Referencja do obiektu na którym próbowano odwołać się do nieistniejącego atrybutu
	 * @var \pjpl\db\a\Encja
	 */
	protected $encja;
	/**
	 * Nieistniejący parametr którego szukałeś w Encji
	 * @var string
	 */
	protected $attribute;
	/**
   * @param string $class_name - Nazwa klasy w której zgłoszono wyjątek, (przeważnie wnętrze obiektu $encja)
   * @param string $function_name - funkcja w której zgłoszono wyjątek
	 * @param \pjpl\db\a\Encja $encja Referencja do obiektu o nazwie $encja
	 * @param string $attribute - nazwa parametru na jakim próbowałeś operować wewnąrz $encja
	 */
	public function __construct($class_name,$function_name, $encja, $attribute) {
		$this->encja = $encja;
		$this->attribute = $attribute;
		parent::__construct($class_name, $function_name, 'Wewnątrz encji : '.$class_name.' nie istnieje zmienna o nazwie : '.$attribute);
	}
	public function getEncja(){
		return $this->encja;
	}
	public function getAttribute(){
		return $this->attribute;
	}
	public function code() {
		return self::ENONEXISTATTRIBUTE;
	}
	public function name() {
		return 'ERR_ENONEXISTATTRIBUTE';
	}

}