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
 * @prace 2014-10-18 Duża przebudowa DependencyTableRecord
 * @prace 2014-10-10 Przenieść kontrolę attrybutów do __set() i __get()
 */
class Zatrudnienie extends Encja{
  /**
   * @param int $stanowisko_id
   * @throws EBadIn
   */
  public function setStanowiskoId($stanowisko_id){
    if(empty($stanowisko_id)){
      $E = new EBadIn(__CLASS__, __FUNCTION__, '$stanowisko_id', 'empty');
      throw $E;
    }
    $this->stanowisko_id = $stanowisko_id;
  }
  /**
   * @param int $pracownik_id
   * @throws EBadIn
   */
  public function setPracownikId($pracownik_id){
    if(empty($pracownik_id)){
      $E = new EBadIn(__CLASS__, __FUNCTION__, '$pracownik_id', 'empty');
      throw $E;
    }
    $this->pracownik_id = $pracownik_id;
  }
  public function setDataOd($data_od){
    $this->data_od = $data_od;
  }
  public function setDataDo($data_do){
    $this->data_do = $data_do;
  }
  public function getStanowiskoId(){
    return $this->stanowisko_id;
  }
  public function getPracownikId(){
    return $this->pracownik_id;
  }
  public function getDataOd(){
    return $this->data_od;
  }
  public function getDataDo(){
    return $this->data_do;
  }

	public static function nullRow() {
		return [
						'stanowisko_id' => NULL,
						'pracownik_id'  => NULL,
						'data_od'       => NULL,
						'data_do'       => NULL
		];
	}

}
class ZatrudnieniaDependence extends DependenceTableRecord{
  public function tabelaId() {
    return 14;
  }
  public function tableName() {
    return 'zatrudnienia';
  }
	public function className() {
		return 'Zatrudnienie';
	}
}
class ZatrudnieniaTable extends Table{

  public static function getInstance() {
    if(!(self::$instance instanceof ZatrudnieniaTable)){
      self::$instance = new ZatrudnieniaTable(new ZatrudnieniaDependence(),DB::getInstance());
    }
    return self::$instance;
  }

	/**
	 * Zwraca idnetyfikator pracownika zatrudnionego na stanowisku w dniu $data
	 * @param int $stanowisko_id
	 * @param date $data
	 * @return Pracownkk || null
	 * @confirm 2014-09-03
	 */
	public function getPracownikNaStanowikuWDniu($stanowisko_id, $data){
		if(empty($stanowisko_id) || empty($data) ){
			return NULL;
		}
		$sql =		"	SELECT MAX( data_od), pracownik_id FROM ".$this->getDI()->tableName()
						.	"	WHERE stanowisko_id = $stanowisko_id "
						. "		AND data_od <= '$data'";
		$stmt = $this->DB->query($sql);
		if( $row = $stmt->fetch(PDO::FETCH_BOTH) ){
			$pracownik_id = $row['pracownik_id'];
			$tPracownicy = PracownicyTable::getInstance();
			$rPracownik = $tPracownicy->getRecord($pracownik_id);
		}else{
			$rPracownik = null;
		}
		return $rPracownik;
	}

	/**
	 * Zwraca tablicę pracowników zatrudnionych na stanowisku w okresie ograniczonym datami
	 * @param int $stanowisko_id - identyfikator stanowiska którego dotyczy zapytanie
	 * @param date $data_od - pierwszy dzień okresu dla którego szykamy pracowników zatrudnionych na stanowisku
	 * @param date $data_do - ostatni dzień okresu dla którego szukamy pracowników zatrudnionych a stanowisku
	 * @return array tablica identyfikatorów pracowników zatrudnionych na stanowisku we wskazanym okresie
	 */
	public function getPracownicyIdInTerm($stanowisko_id,$data_od, $data_do){
		if($data_do === NULL){
			$data_do = date('Y-m-d');
		}
		$sql =		"	SELECT pracownik_id FROM ".$this->getDI()->tableName()." "
						. "	WHERE stanowisko_id = '".$stanowisko_id."' "
						. "		AND ("
						. "			( "
						.	"				data_od >= '$data_od' AND data_do <= '$data_od'"
						. "			) "
						. "			OR"
						. "			("
						. "				data_do >= '$data_do' AND data_od >= '$data_do'"
						. "			) "
						. "		) ";
		// @todo Rozbudować klasę DBTable by to zapytanie mogło wykonać się za pomocą paramterów przekazanych do jej funkcji
		$stmt = $this->DB->query($sql);
		$pracownicy_ids = array();
		while ($row = $stmt->fetch()){
			$pracownicy_ids[] = $row['pracownik_id'];
		}

		return $pracownicy_ids;
	}
}