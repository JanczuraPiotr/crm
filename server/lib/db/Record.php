<?php
namespace crmsw\lib\db; // @todo przenieść do namespace pjpl\db i zmienić nazwę na RecordTime
use pjpl\db\a\Encja;
use crmsw\lib\db\a\Table;
/**
 * Rekord opisuje dane wraz z ich identyfikatorem w bazie danych. Rekord wspiera kontrolę czasu modyfikacji rekordu.
 *
 * Przeznaczeniem rekordu jest obsługa czasów modyfikacji ale powinno to być zrobione w bibliotece ogólnej a nie w zasięgu jednego projektu. Zostanie przeniesiony
 * @package crmsw
 * @subpackage lib
 * @author Piotr Janczura <piotr@janczura.pl>
 * @done 2014-12-16
 */
class Record extends \pjpl\db\Record{
	/**
	 * @var int
	 */
	protected $create;
	/**
	 * @var int
	 */
	protected $update;
	/**
	 *
	 * @param int $id
	 * @param int $create
	 * @param int $update
	 * @param Encja $Encja
	 * @param Table $Table
	 */
	public function __construct($id, $create, $update, Encja $Encja, $Table) {
		parent::__construct($id, $Encja, $Table);
		$this->create = $create;
		$this->update = $update;
	}
	/**
	 * Czas w mikrosekundach utworzenia rekordy
	 * @return int
	 */
	public function getCreate(){
		return $this->create;
	}
	/**
	 * Czas w mikrosekundach ostatniej aktualizacji rekordu. Jeżeli === NULL to rekord nie był aktualizowany
	 * @return int
	 */
	public function getUpdate(){
		return $this->update;
	}
  public function softDelete($data_do){
    $this->getEncja()->setDataDo($data_do);
    $this->updateRecord($this);
  }
  public function softDeleteImmediately($data_do){
    $this->getEncja()->setDataDo($data_do);
    $this->updateRecordImmediately($this);
  }
}