<?php
namespace crmsw\logic\tasks;
use crmsw\lib\a\beta\BusinessLogic;
use pjpl\db\a\Encja;
use pjpl\db\Where;
use pjpl\e\NoEncja;
/**
 * Odnotowuje wykonanie kroku zadania.
 *
 * Oznacza ostatni krok zadania jako wykonany i dodaje nowy rekord informujący o konieczności wykonania kolejnego kroku o wskazanym czasie.
 * Wykonanie kroku polega no oznaczeniu poprzedniego jako wykonany poprzez ustawienie zmiennej "data_step" na czas w którym wykonano krok i utworzeniu
 * nowego rekordu w którym "data_step" jest null.
 *
 * @package crmsw
 * @subpackage logic
 * @author Piotr Janczura <piotr@janczura.pl>
 * @confirm 2014-12-20
 */
class NextStep extends BusinessLogic{
  public function __construct() {
    parent::__construct();
  }
	protected function _algorithm(& $NewStep){
		try {
			$this->DB->beginTransaction();
			$ret = $this->plannedStep($this->previousStepEnd($NewStep));
			$this->DB->commit();
			return $ret;
		} catch (\Exception $ex) {
			$this->DB->rollBack();
			$this->success = false;
			throw $ex;
		}
	}
	protected function _init() {
		$this->ZadaniaTable = $this->DB->tableZadania();
		$DateTime = new \DateTime();
		$this->teraz = $DateTime->format('Y-m-d H:i:s');
		$DateTime->add(new \DateInterval('P1D'));
		$this->data_next_step = $DateTime->format('Y-m-d').' 00:00:00'; // Gdy data następnego kroku nie jest podana domyślnie należy wyznaczyć następny dzień

		foreach ($this->arguments as $key => $attributes) {
			if( ! isset($attributes['data_step'])){
				$attributes['data_step'] = $this->teraz;
			}
			$this->dataPrepared[$key] = \Zadanie::create($attributes);
		}
	}
	/**
	 * Oznacza ostatni-poprzedni krok zadania jako wykonany.
	 *
	 * Czas wykonania zadania podany jest z data_step przełanego w $_REQUEST potwierdzającym wykonanie kroku ale gdy wartość taka nie została
	 * ustawiona to czas wykonania kroku ustawiany jest na data('Y-m-d H:i:s');
	 * @param Encja $eNewStep - Encja w formie nadesłanej przez klienta informująca o wykonaniu kroku.
	 * @return Encja Encja w formie nadesłanej przez klienta infiormująca o wykonaniu kroku.
	 * @throw ENoEncja
	 * @confirm 2014-09-10 Przebudowa klasy Zadanie
	 */
	protected function previousStepEnd(Encja $eNewStep){
		$this->ZadaniaTable->where(new Where([
				[
						'attribute' => 'nr_zadania',
						'operator'  => '=',
						'value'     => $eNewStep->nr_zadania
				],[
						'attribute' => 'data_step',
						'operator'  => '=',
						'value'     => NULL
				]
		]))->load();

		$rPrevStep = $this->ZadaniaTable->getRecordLast();
		if($rPrevStep === NULL){
			$Where = new Where('nr_zdania', '=', $eNewStep->nr_zadania);
			$Where->append('and', 'data_step', '=', NULL);
			throw new NoEncja(__CLASS__, __FUNCTION__, 'zadania',$Where);
		}
		$rPrevStep->data_step = $eNewStep->data_step;
		$rPrevStep->notatka = $eNewStep->notatka;
		$rPrevStep->updateImmediately();

		// Przetwarzanie rekordy dla poprzedniego kroku zakończyło się ustawieniem notatki i nadaniem czasu wykonania kroku
		// Teraz na podstawie opisu nowego kroku : $eNewStep i znajomości poprzednio wykonanego kroku tworzymy encję opisującą nowy krok
		$ePrevStep = clone $rPrevStep->Encja;
		if( ! $eNewStep->stanowisko_id ){
			// Nie nadeszły informacje o stanowisku do którego przypisane jest zadania więc w następnym kroku będzie obsługiwane na poprzednim stanowisku
			$eNewStep->stanowisko_id = $ePrevStep->stanowisko_id;
		};
		if( ! $eNewStep->status_zadania_id ){
			// Obowiązuje poprzedni status zadania
			$eNewStep->status_zadania_id = $ePrevStep->status_zadania_id;
		};
		if( $rPrevStep->data_next_step !== NULL && $eNewStep->data_next_step === NULL){
			$eNewStep->data_next_step = $rPrevStep->data_next_step;
		}
		$eNewStep->klient_id = $ePrevStep->klient_id;
		$eNewStep->produkt_id = $ePrevStep->produkt_id;
		$eNewStep->data_step = NULL;
		$eNewStep->notatka = NULL;

		return $eNewStep;
	}
	/**
	 * Buduje rekord opisujący nowy krok zadania.
	 *
	 * @param Encja $eNewStep - Encja opisująca krok oczekujący na wykonanie
	 * @return int - Identyfikator rekordu pod jakim zapisano zaplanowany krok zadania
	 */
	protected function plannedStep(Encja $eNewStep){
		if($eNewStep->data_next_step === NULL){
			$eNewStep->data_next_step = $this->data_next_step;
		}
		return $this->ZadaniaTable->createRecordImmediately($eNewStep)->getId();
	}
	protected function _externalArguments(& $arguments) {
		$data = json_decode($arguments['data'],true);
		foreach ($data as $key => $attributes) {
			$raw = array();
			// nr zadanie nie może się zmienić ale jest to wartość po której zadanie jest rozpozanwana
			if(isset($attributes['nr_zadania'])){
				$raw['nr_zadania'] = $this->Firewall->int($attributes['nr_zadania']);
			}
			if(isset($attributes['stanowisko_id'])){
				$raw['stanowisko_id'] = $this->Firewall->int($attributes['stanowisko_id']);
			}
			if(isset($attributes['notatka'])){
				$raw['notatka'] = $this->Firewall->string($attributes['notatka']);
				// @todo Wykonanie kolejnego kroku wiąże się z dodaniem notatki - przetestować jak sever reaguje na brak notatki podczas aktualizowania bazy
			}
			if(isset($attributes['data_next_step'])){
				$raw['data_next_step'] = $this->Firewall->date($attributes['data_next_step']);
			}
			if(isset($attributes['data_step'])){
				$raw['data_step'] = $this->Firewall->date($attributes['data_step']);
			}
			if(isset($attributes['status_zadania_id'])){
				$raw['status_zadania_id'] = $this->Firewall->int($attributes['status_zadania_id']);
			}
			$this->arguments[$key] = $raw;
		}
	}
	protected function _internalArguments(& $arguments) {
		foreach ($arguments as $key => $value) {
			$this->arguments[$key] = $value;
		}
	}

	/**
	 * Chwila na którą obiekt ustawi czasy tworzenia zadania
	 * @var date-string
	 */
	protected $teraz;
	/**
	 * Czas kiedy krok zadania powinien być wykonany
	 * @var date-string
	 */
	protected $data_next_step;
	/**
   * @var \ZadaniaTable
   */
  protected $ZadaniaTable;
}