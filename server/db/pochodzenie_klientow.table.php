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
 * @done 2014-12-31
 * @task 2014-10-10 Przenieść kontrolę attrybutów do __set() i __get()
 */

class PochodzenieKlienta extends Encja{
	public static function nullRow() {
		return [
						'symbol' => NULL,
						'opis'   => NULL
		];
	}

	public function getOpis(){
		return $this->opis;
	}
	public function getSymbol(){
		return $this->symbol;
	}
	/**
	 * @param char[30] $opis
	 * @throws ENotSet
	 */
	public function setOpis($opis){
		if(empty($opis)){
			throw new ENotSet(__CLASS__,__METHOD__,'$opis','empty');
		}else{
			$this->opis = $opis;
		}
	}
	/**
	 * @param char[10] $symbol
	 * @throws ENotSet
	 */
	public function setSymbol($symbol){
		if(isset($sumbol)){
			throw new ENotSet(__CLASS__,__METHOD__,'$symbol','empty');
		}  else {
			$this->symbol = $symbol;
		}
	}
}
class PochodzenieKlientaDependence extends DependenceTableRecord{
	public function tabelaId() {
		return 28;
	}
	public function tableName() {
		return 'pochodzenie_klientow';
	}
	public function className() {
		return 'PochodzenieKlienta';
	}
}

class PochodzenieKlientowTable extends Table{
	public static function getInstance() {
    if(!(self::$instance instanceof PochodzenieKlientaTable)){
      self::$instance = new PochodzenieKlientowTable(new PochodzenieKlientaDependence(),DB::getInstance());
    }
    return self::$instance;
	}

}