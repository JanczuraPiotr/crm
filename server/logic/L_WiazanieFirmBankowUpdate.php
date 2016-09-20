<?php
/**
 * @package crmsw
 * @subpackage logic
 * @author Piotr Janczura <piotr@janczura.pl>
 * @task 2014-10-20 Przebudowa Table do obsługi zapytań preparowanych
 * @task 2014-10-20 Duża przebudowa DependencyTableRecord
 * @todo \pjpl\a\BusinessLogic <<< \crmsw\lib\a\BusinessLogic z usunięciem dziesdziczenia po klasie ...\beta\BusinessLogic
 */

class L_WiazanieFirmBankowUpdate extends \crmsw\lib\a\BusinessLogic{
	public function __construct() {
		parent::__construct();
		$this->BankiOddzialyFirmyOddzialyTable = $this->DB->tableBankiOddzialyFirmyOddzialy();
	}
	protected function logic() {

	}
	public function fromRequest(&$_request) {

	}
	/**
	 * @var BankiOddzialyFirmyOddzialyTable
	 */
	protected $BankiOddzialyFirmyOddzialyTable;
}