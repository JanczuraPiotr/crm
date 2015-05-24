<?php
namespace pjpl\e;
/**
 * Wyjątek ogólny.
 *
 * Wraper na \Exception celem ujednolicenia interfejsu
 *
 * @package pjpl
 * @subpackage exceptions
 * @author Piotr Janczura <piotr@janczura.pl>
 * @confirm 2014-10-28
 */
class General extends \pjpl\e\a\E{
	protected function code() {
		return self::EGENERAL;
	}

	protected function name() {
		return 'ERR_EGENERAL';
	}

}