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
class StatusKlienta extends Encja{
//	private $symbol;
//  private $status;
//  private $opis;
//  public function __construct($symbol,$status,$opis) {
//		$this->setSymbol($symbol);
//    $this->setStatus($status);
//    $this->setOpis($opis);
//  }
//	public function setSymbol($symbol){
//		if(empty($symbol)){
//			throw new ENotSet(__CLASS__,__FUNCTION__,'$symbol','empty');
//		}
//		$this->symbol = $symbol;
//	}
//	public function setStatus($status){
//		if(empty($status)){
//			if($status != 0){
//				throw new ENotSet(__CLASS__,__FUNCTION__,'$status','empty');
//			}
//		}
//    $this->status = $status;
//  }
//  public function setOpis($opis){
//		if(empty($opis)){
//			throw new ENotSet(__CLASS__,__METHOD__,'$opis','empty');
//		}
//    $this->opis = $opis;
//  }
//	public function getSymbol(){
//		return $this->symbol;
//	}
//	public function getStatus(){
//    return $this->status;
//  }
//  public function getOpis(){
//    return $this->opis;
//  }

	public static function nullRow() {
		return [
						'symbol' => NULL,
						'status' => NULL,
						'opis'   => NULL
		];
	}

}
class StatusKlientaDependency extends DependenceTableRecord{
  public function tabelaId() {
    return 20;
  }
  public function tableName() {
    return 'statusy_klientow';
  }
	public function className() {
		return 'StatusKlienta';
	}
}
class StatusyKlientowTable extends Table{
  public static function getInstance() {
    if( ! (self::$instance instanceof StatusyKlientowTable)){
      self::$instance = new StatusyKlientowTable(new StatusKlientaDependency(),DB::getInstance());
    }
    return self::$instance;
  }

}