<?php
// @todo namespace crmsw\db;
use pjpl\db\a\Encja;
use crmsw\lib\db\a\DependenceTableRecord;
use crmsw\lib\db\a\Table;
use crmsw\lib\db\DB;
use crmsw\lib\db\Record;

/**
 * @package crmsw
 * @subpackage database
 * @author Piotr Janczura <piotr@janczura.pl>
 * @work 2014-10-10 Przenieść kontrolę attrybutów do __set() i __get()
 */
class Zadanie extends Encja{
	public function __get($attribute) {
		switch ($attribute){
			case 'nr_zadania':
			case 'klient_id':
			case 'produkt_id':
			case 'status_zadania_id':
				return (int)$this->attributes[$attribute];

			case 'stanowisko_id':
				$s = $this->attributes['stanowisko_id'];
				if( $s !== NULL && $s > 0){
					$s = (int)$s;
				}else{
					$s = NULL;
				}
				return $s;

			case 'data_step':
				$d = $this->attributes['data_step'];
				if( $d === NULL || strtoupper($d) === 'NULL'){ // @test 2014-11-04 Założyłęm bez dotkowego testu że zmienna może być stringiem i wykonałem na niej strtoupper()
					$d = NULL;
				}
				return $d;

			default:
				return parent::__get($attribute);
		}
	}
	public function __set($attribute, $value) {
		switch ($attribute){
			case 'nr_zadania':
			case 'klient_id':
			case 'produkt_id':
			case 'status_zadania_id':
				$this->attributes[$attribute] = (int)$value;
				break;

			case 'stanowisko_id':
				if( $value === 0 || $value === NULL || strtoupper($value) === 'NULL'){ // @test 2014-11-04 Założyłęm bez dotkowego testu że zmienna może być stringiem i wykonałem na niej strtoupper()
					$this->attributes['stanowisko_id'] = NULL;
				}else{
					$this->attributes['stanowisko_id'] = (int)$value;
				}
				break;

			case 'data_step':
				if( $value === 0 || $value === NULL || strtoupper($value) === 'NULL'){ // @test 2014-11-04 Założyłęm bez dotkowego testu że zmienna może być stringiem i wykonałem na niej strtoupper()
					$this->attributes['data_step'] = NULL;
				}else{
					$this->attributes['data_step'] = $value;
				}
				break;

			case 'data_next_step':
				// @confirm 2014-09-03 Zabroniłem wstawiać pustą wartość dla data_next_step i w takim przypadku wstawiam data('Y-m-d')
				if($value === null){
					$DateTime = new \DateTime();
					$DateTime->add(new \DateInterval('P1D'));
					$this->attributes[$attribute] = $DateTime->format('Y-m-d').' 00:00:00';
				}else{
					$this->attributes[$attribute] = $value;
				}
				break;

			default :
				parent::__set($attribute, $value);
		}
	}
	static public function nullRow(){
		return array(
						'nr_zadania'        => NULL,
						'klient_id'         => NULL,
						'stanowisko_id'     => NULL,
						'produkt_id'        => NULL,
						'status_zadania_id' => NULL,
						'notatka'           => NULL,
						'data_next_step'    => NULL,
						'data_step'         => NULL
		);
	}
}

class ZadaniaDependence extends DependenceTableRecord{
	public function tabelaId() {
    return 25;
  }
  public function tableName() {
    return 'zadania';
  }
	public function className() {
		return 'Zadanie';
	}

}

class ZadaniaTable extends Table{
  public static function getInstance() {
    if(!(self::$instance instanceof ZadaniaTable)){
      self::$instance = new ZadaniaTable(new ZadaniaDependence(), DB::getInstance());
    }
    return self::$instance;
  }
	/**
	 * Zwraca rekord opisujący ostatni krok w zadaniu
	 * @param int $nr_zadania
	 * @var \crmsw\lib\db\Record
	 */
	public function getLastStep($nr_zadania){
		$this->__DEPRECIATE__setFiltrKeyValueAndAndRead(array('nr_zadania'=>$nr_zadania));// @depreciate
		return $this->getRecordLast();
	}

}