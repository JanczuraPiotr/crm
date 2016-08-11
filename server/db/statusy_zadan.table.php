<?php
// @todo namespace crmsw\db;
// @todo use \pjpl\db\Encja;
use crmsw\lib\db\a\DependenceTableRecord;
use crmsw\lib\db\a\Table;
use crmsw\lib\db\DB;
use crmsw\lib\db\a\Encja;
use crmsw\lib\db\Record;
/**
 * @package crmsw
 * @subpackage database
 * @author Piotr Janczura <piotr@janczura.pl>
 * @work 2014-10-10 Przenieść kontrolę attrybutów do __set() i __get()
 * @work 2014-10-18 Duża przebudowa DependencyTableRecord
 */
class StatusZadania extends Encja{
	const NIEZAINTERESOWANY					= -1;
	const NIESPRAWDZONY							= 0;
	const MOZEKIEDYS								= 1;
	const ZASTANAWIAJACY						= 2;
	const ZAINTERESOWANY						= 3;
	const ZDECYDOWANY								= 4;
	const PROCEDOWANY								= 5;
	const ZAKONCZONE								= 6;
  public function setSymbol($symbol){
		if(empty($symbol)){
			throw new ENotSet(__CLASS__,__FUNCTION__,'$symbol','empty');
		}
		$this->symbol = $symbol;
	}
	public function setStatus($status){
    $this->status = $status;
  }
  public function setOpis($opis){
		if(empty($opis)){
			throw new ENotSet(__CLASS__,__METHOD__,'$opis','empty');
		}
    $this->opis = $opis;
  }
	public function getSymbol(){
		return $this->symbol;
	}
	public function getStatus(){
    return $this->status;
  }
  public function getOpis(){
    return $this->opis;
  }

	public static function nullRow() {
		return [
						'symbol' => NULL,
						'status' => NULL,
						'opis'   => NULL
		];
	}
}
class StatusZadaniaDependence extends DependenceTableRecord{
  public function tabelaId() {
    return 20;
  }
  public function tableName() {
    return 'statusy_zadan';
  }

	public function className() {
		return 'StatusZadania';
	}

}
class StatusyZadanTable extends Table{
  public static function getInstance() {
    if(!(self::$instance instanceof StatusyZadanTable)){
      self::$instance = new StatusyZadanTable(new StatusZadaniaDependence(),DB::getInstance());
    }
    return self::$instance;
  }

}