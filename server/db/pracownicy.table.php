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
 * @confirm 2014-08-27 Rzutowanie zmiennych inicjujących int w metodach set...
 */
class Pracownik extends Encja{
	public function getFirmaId(){
		return $this->firma_id;
	}
	public function getNazwisko(){
		return $this->nazwisko;
	}
	public function getImie(){
		return $this->imie;
	}
	public function getPesel(){
		return $this->pesel;
	}
	public function getKodPoczt(){
		return $this->kod_poczt;
	}
	public function getMiejscowosc(){
		return $this->miejscowosc;
	}
	public function getUl(){
		return $this->ul;
	}
	public function getNrB(){
		return $this->nr_b;
	}
	public function getNrL(){
		return $this->nr_l;
	}
	public function getTel(){
		return $this->tel;
	}
	public function getEmail(){
		return $this->email;
	}
	public function getLogin(){
		return $this->login;
	}
	public function getHaslo(){
		return $this->haslo;
	}
	public function getDataOd(){
		return $this->data_od;
	}
	public function getDataDo(){
		return $this->data_do;
	}
	public function setFirmaId($firma_id){
		if(empty($firma_id)){
			throw new \ENotSet(__CLASS__,__FUNCTION__,'$firma_id','empty');
		}
		$this->firma_id = (int)$firma_id;
	}
	/**
   * @param string $nazwisko
   * @throws EBadIn
   */
  public function setNazwisko($nazwisko){
    if(empty($nazwisko)){
      $E = new EBadIn(__CLASS__, __FUNCTION__, '$nazwisko', $nazwisko);
      throw $E;
    }
    $this->nazwisko = $nazwisko;
  }
  /**
   * @param string $imie
   * @throws EBadIn
   */
  public function setImie($imie){
    if(empty($imie)){
      $E = new EBadIn(__CLASS__, __FUNCTION__, '$imie', $imie);
      throw $E;
    }
    $this->imie = $imie;
  }
  /**
   * @param string $pesel
   * @throws EBadIn
   */
  public function setPesel($pesel){
    if(empty($pesel)){
      $E = new EBadIn(__CLASS__, __FUNCTION__, '$pesel', $pesel);
      throw $E;
    }
    $this->pesel = $pesel;
  }
  public function setKodPoczt($kod_poczt){
    $this->kod_poczt = $kod_poczt;
  }
  public function setMiejscowosc($miejscowosc){
    $this->miejscowosc = $miejscowosc;
  }
  public function setUl($ul){
    $this->ul = $ul;
  }
  public function setNrB($nr_b){
    $this->nr_b = $nr_b;
  }
  public function setNrL($nr_l){
    $this->nr_l = $nr_l;
  }
  public function setTel($tel){
    $this->tel = $tel;
  }
  public function setEmail($email){
    $this->email = $email;
  }
	public function setLogin($login){
		if(empty($login)){
			$this->login = NULL;
		} else {
			$this->login = $login;
		}
	}
	public function setHaslo($haslo){
		if(empty($haslo)){
			$this->haslo = null;
		}else{
			$this->haslo = $haslo;
		}
	}
	public function setDataOd($data_od){
    $this->data_od = $data_od;
  }
  public function setDataDo($data_do){
    $this->data_do = $data_do;
  }

	public static function nullRow() {
		return [
						'firma_id'    => NULL,
						'nazwisko'    => NULL,
						'imie'        => NULL,
						'pesel'       => NULL,
						'kod_poczt'   => NULL,
						'miejscowosc' => NULL,
						'ul'          => NULL,
						'nr_b'        => NULL,
						'nr_l'        => NULL,
						'tel'         => NULL,
						'email'       => NULL,
						'login'       => NULL,
						'haslo'       => NULL,
						'data_od'     => NULL,
						'data_do'     => NULL
		];
	}

}

class PracownicyDependence extends DependenceTableRecord{
  public function tabelaId() {
    return 11;
  }
  public function tableName() {
    return 'pracownicy';
  }
	public function className() {
		return 'Pracownik';
	}
}

class PracownicyTable extends Table{
  public static function getInstance() {
    if(!(self::$instance instanceof PracownicyTable)){
      self::$instance = new PracownicyTable(new PracownicyDependence(),DB::getInstance());
    }
    return self::$instance;
  }

}