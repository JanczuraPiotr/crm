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
 * @prace 2014-10-10 Przenieść kontrolę attrybutów do __set() i __get()
 * @prace 2014-10-18 Duża przebudowa DependencyTableRecord
 */
class OsobaPowiazana extends Encja{
	public static function nullRow() {
		return [
						'klient_id'   => NULL,
						'nazwa'       => NULL,
						'imie'        => NULL,
						'pesel'       => NULL,
						'nip'         => NULL,
						'kod_poczt'   => NULL,
						'miejscowosc' => NULL,
						'ul'          => NULL,
						'nr_b'        => NULL,
						'nr_l'        => NULL,
						'email'       => NULL,
						'telkom'      => NULL,
						'teldom'      => NULL,
						'telpraca'    => NULL
		];
	}

//  public function getKlientId(){
//    return $this->klient_id;
//  }
//  public function getNazwa(){
//    return $this->nazwa;
//  }
//  public function getImie(){
//    return $this->imie;
//  }
//  public function getPesel(){
//    return $this->pesel;
//  }
//  public function getNip(){
//    return $this->nip;
//  }
//  public function getKodPoczt(){
//    return $this->kod_poczt;
//  }
//  public function getMiejscowosc(){
//    return $this->miejscowosc;
//  }
//  public function getUl(){
//    return $this->ul;
//  }
//  public function getNrB(){
//    return $this->nr_b;
//  }
//  public function getNrL(){
//    return $this->nr_l;
//  }
//  public function getEmail(){
//    return $this->email;
//  }
//  public function getTelkom(){
//    return $this->telkom;
//  }
//  public function getteldom(){
//    return $this->teldom;
//  }
//  public function gettelpraca(){
//    return $this->telpraca;
//  }
//  /**
//   * @param int $klient_id
//   * @throws EBadIn
//   */
//  public function setKlientId($klient_id){
//    if(empty($klient_id)){
//      throw new EBadIn(__CLASS__,__FUNCTION__,'$klient_id','empty');
//    }
//    $this->klient_id = $klient_id;
//  }
//  /**
//   * @param sting $nazwa
//   * @throws EBadIn
//   */
//  public function setNazwa($nazwa){
//    if(empty($nazwa)){
//      $E = new EBadIn(__CLASS__, __FUNCTION__, '$nazwa', 'empty');
//      throw $E;
//    }
//    $this->nazwa = $nazwa;
//  }
//  /**
//   * @param string $imie
//   */
//  public function setImie($imie){
//		if(empty($imie)){
//			$this->imie = NULL;
//		}else{
//			$this->imie = $imie;
//		}
//  }
//  public function setPesel($pesel){
//		if(empty($pesel)){
//			$this->pesel = NULL;
//		}else{
//			$this->pesel = $pesel;
//		}
//  }
//  public function setNip($nip){
//		if(empty($nip)){
//			$this->nip = NULL;
//		}else{
//			$this->nip = $nip;
//		}
//  }
//  public function setKodPoczt($kod_poczt){
//		if(empty($kod_poczt)){
//			$this->kod_poczt = NULL;
//		}else{
//			$this->kod_poczt = $kod_poczt;
//		}
//  }
//  /**
//   * @param int $miejscowosc
//   */
//  public function setMiejscowosc($miejscowosc){
//    if(empty($miejscowosc)){
//			$this->miejscowosc = null;
//    }else{
//			$this->miejscowosc = $miejscowosc;
//		}
//  }
//  public function setUl($ul){
//		if(empty($ul)){
//			$this->ul = null;
//		}else{
//			$this->ul = $ul;
//		}
//  }
//  public function setNrB($nr_b){
//		if(empty($nr_b)){
//			$this->nr_b = null;
//		}else{
//			$this->nr_b = $nr_b;
//		}
//  }
//  public function setNrL($nr_l){
//	  if(empty($nr_l)){
//			$this->nr_l = NULL;
//		}else{
//			$this->nr_l = $nr_l;
//		}
//  }
//  public function setEmail($email){
//		if(empty($email)){
//			$this->email = NULL;
//		}else{
//			$this->email = $email;
//		}
//  }
//  /**
//   * @param string $telkom
//   */
//  public function setTelkom($telkom){
//		if(empty($telkom)){
//			$this->telkom = NULL;
//		}else{
//			$this->telkom = $telkom;
//		}
//  }
//  /**
//   * @param string $teldom
//	 */
//  public function setTeldom($teldom){
//		if(empty($teldom)){
//			$this->teldom = null;
//		}else{
//			$this->teldom = $teldom;
//		}
//  }
//  /**
//   * @param string $telpraca
//   */
//  public function setTelpraca($telpraca){
//		if(empty($telpraca)){
//			$this->telpraca = null;
//		}else{
//			$this->telpraca = $telpraca;
//		}
//  }

}
class OsobyPowiazaneDependence extends DependenceTableRecord{
  public function tabelaId() {
    return 21;
  }
  public function tableName() {
    return 'osoby_powiazane';
  }
	public function className() {
		return 'OsobaPowiazana';
	}
}
class OsobyPowiazaneTable extends Table{
  public static function getInstance() {
    if(!(self::$instance instanceof OsobyPowiazaneTable)){
      self::$instance = new OsobyPowiazaneTable(new OsobyPowiazaneDependence(),DB::getInstance());
    }
    return self::$instance;
  }
}