<?php
namespace pjpl\e\db;
/**
 * Wyjątek domyślny dla błędów pochodzących z baz danych.
 *
 * @package pjpl
 * @subpackage exceptions
 * @author Piotr Janczura <piotr@janczura.pl>
 * @confirm 2014-09-11
 */
class General extends \pjpl\e\a\E{
  public function code(){
		return self::EDB_GENERAL;
	}
	public function name(){
		return 'EDB_GENETRAL';
	}
}