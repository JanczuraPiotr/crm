<?php
namespace crmsw\logic\tasks;
use crmsw\lib\a\beta\BusinessLogic;

/**
 * Przypisanie nieprzydzielonego zadania do stanowiska pracy.
 *
 * Przekazanie zadania na instanowisko za pomocą \crmsw\logic\Transfer*
 * @package crmsw
 * @subpackage logic
 * @author Piotr Janczura <piotr@janczura.pl>
 * @done 2014-09-18
 */
class Allocate extends BusinessLogic{
  public function __construct() {
    parent::__construct();
    $this->ZadaniaTable = $this->DB->tableZadania();
    $this->ZadanieOpisyTable = $this->DB->tableZadaniaOpis();
  }
	protected function _algorithm(&$arguments) {
		// @err Możliwe jest ponowne przypisanie zadania do tego samego stanowiska w każdym wywołaniu
		// Słabo to wyszło. Trzeba na nowo przemyśleć wzajemne wywoływanie logik biznesowych
		$we = true;
 		$ret = $this->NextStep->algorithm($we);
		if( isset($arguments)){
			$arguments = $we;
		}
		return $we['arguments']->nr_zadania;
	}
	protected function _init(){
		$this->dataPrepared = array();
		foreach ($this->arguments as $key => $przydzial) {
			$this->dataPrepared[$key]['stanowisko_id'] = $przydzial['stanowisko_id'];
			$this->dataPrepared[$key]['nr_zadania'] = $przydzial['nr_zadania'];
			$this->dataPrepared[$key]['notatka'] = 'przypisanie zadania do stanowiska';
			$this->dataPrepared[$key]['data_step'] = date('Y-m-d H:i:s');
		}
		$this->NextStep = new NextStep();
		$this->NextStep->internalArguments($this->dataPrepared);
	}
	protected function _externalArguments(&$arguments) {
    if(isset($arguments)){
			$zadania = json_decode($arguments['zadania']);
			if(isset($arguments['stanowisko_id'])){
				$stanowisko_id = $this->Firewall->int($arguments['stanowisko_id']);
			}else{
				$stanowisko_id = NULL;
			}
      foreach ($zadania as $key => $value) {
        $row = array();
				$row['stanowisko_id'] = $stanowisko_id;
				$row['nr_zadania'] = $this->Firewall->int($value);
        $this->arguments[$key] = $row;
      }
    }
	}
	protected function _internalArguments(&$arguments) {
		foreach ($arguments as $key => $value) {
			$this->arguments[$key] = $value;
		}
	}
	/**
	 * @var NextStep
	 */
	protected $NextStep;
	/**
   * @var \ZadaniaTable
   */
  protected $ZadaniaTable;
  /**
   * @var \ZadaniaOpisyTable
   */
  protected $ZadanieOpisyTable;
}