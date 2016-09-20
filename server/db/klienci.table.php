<?php
// @todo namespace crmsw\db;
use crmsw\lib\db\a\DependenceTableRecord;
use crmsw\lib\db\a\Table;
use crmsw\lib\db\DB;
use crmsw\lib\db\a\Encja; // @todo use \pjpl\db\a\Encja;
use crmsw\lib\db\Record;
/**
 * @package crmsw
 * @subpackage database
 * @author Piotr Janczura <piotr@janczura.pl>
 * @done 2014-12-16
 * @task 2014-10-10 Przenieść kontrolę attrybutów do __set() i __get()
 */
class Klient extends Encja{
	public static function nullRow() {
		return [
						'nazwa'                   => NULL,
						'imie'                    => NULL,
						'pesel'                   => NULL,
						'nip'                     => NULL,
						'kod_poczt'               => NULL,
						'miejscowosc'             => NULL,
						'ul'                      => NULL,
						'nr_b'                    => NULL,
						'nr_l'                    => NULL,
						'email'                   => NULL,
						'telkom'                  => NULL,
						'teldom'                  => NULL,
						'telpraca'                => NULL,
						'opis'                    => NULL,
						'pochodzenie_klientow_id' => NULL,
						'firma_id'                => NULL,
						'statusy_klientow_id'     => NULL,
						'data_od'                 => NULL,
						'data_do'                 => NULL
		];
	}

//  /**
//   * @param string $nazwa
//   * @throws EBadIn
//   */
//  public function setNazwa($nazwa){
//    if(empty($nazwa)){
//      $E = new \pjpl\depreciate\EBadIn(__CLASS__, __FUNCTION__, '$nazwa', 'empty');
//      throw $E;
//    }
//    $this->nazwa = $nazwa;
//  }
//  /**
//   * @param string $imie
//   */
//  public function setImie($imie){
//		if(empty($imie)){
//			$this->imie = null;
//		}{
//			$this->imie = $imie;
//		}
//  }
//  public function setPesel($pesel){
//		if(empty($pesel)){
//			$this->pesel = null;
//		}else{
//			$this->pesel = $pesel;
//		}
//  }
//  public function setNip($nip){
//		if(empty($nip)){
//			$this->nip = null;
//		}else{
//			$this->nip = $nip;
//		}
//  }
//  public function setKodPoczt($kod_poczt){
//		if(empty($kod_poczt)){
//			$this->kod_poczt = null;
//		}else{
//			$this->kod_poczt = $kod_poczt;
//		}
//  }
//  /**
//   * @param string $miejscowosc
//   */
//  public function setMiejscowosc($miejscowosc){
//		if(empty($miejscowosc)){
//			$this->miejscowosc = null;
//		}else{
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
//   */
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
//  public function setOpis($opis){
//		if(empty($opis)){
//			$this->opis = null;
//		}else{
//			$this->opis = $opis;
//		}
//  }
//  public function setPochodzenieKlientowId($pochodzenie_klientow_id){
//		if(empty($pochodzenie_klientow_id)){
//			$this->pochodzenie_klientow_id = 0;
//		}else{
//			$this->pochodzenie_klientow_id = $pochodzenie_klientow_id;
//		}
//  }
//  public function setFirmaId($firma_id){
//		if(empty($firma_id)){
//			$this->firma_id = NULL;
//		}else{
//			$this->firma_id = $firma_id;
//		}
//  }
//  /**
//   * @param int $statusy_klientow_id
//   */
//  public function setStatusyKlientowId($statusy_klientow_id){
//    if(empty($statusy_klientow_id)){
//			$this->statusy_klientow_id = 0;
//    }else{
//			$this->statusy_klientow_id = $statusy_klientow_id;
//		}
//  }
//  public function setDataOd($data_od){
//    $this->data_od = $data_od;
//  }
//  public function setDataDo($data_do){
//    $this->data_do = $data_do;
//  }

}

class KlientDependence extends DependenceTableRecord{
  public function tabelaId() {
    return 19;
  }
  public function tableName() {
    return 'klienci';
  }
	public function className() {
		return 'Klient';
	}
}
class KlienciTable extends Table{
  public static function getInstance() {
    if( ! (self::$instance instanceof KlienciTable)){
      self::$instance = new KlienciTable(new KlientDependence(),DB::getInstance());
    }
    return self::$instance;
  }
}