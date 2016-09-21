<?php
namespace pjpl\a;
use pjpl\db\Where;
use pjpl\Firewall;
/**
 * @package pl.janczura.piotr (pjpl)
 * @subpackage logic
 * @author Piotr Janczura <piotr@janczura.pl>
 * @done 2014-12-20
 * @doc 2014-10-28
 */
 // @todo Przebudować a ewentualnie usunąć z interfejsu obsługę transakcji
abstract class BusinessLogic{
	/**
	 * @param DB $DB
	 * @param Firewall $Firewall
	 */
  public function __construct($DB,$Firewall){
    $this->dataIn = array();
    $this->dataOut = array();
    $this->DB = $DB;
    $this->Firewall = $Firewall;
  }
  /**
   * Przekaż tablicę $_REQUEST do tej metody i tu dokonaj jej rozbiowu na podstawowe dane i na ich podstawie utwórz parametry klasy.
   */
  abstract public function fromRequest(&$request);
  /**
   * Tu wstaw wszystkie operacje biznesowe.
   * Wykorzystaj parametry klasy utworzone w metodzie ABuissnesLogic::fromResponse().
   * Jako wynik funkcji zwróć obiekt JSON w formie oczwkiwanej przez klienta wywołującego obiekt.
   * json_encode(array('ret'=> kod wykonanej operackji : OK lub ERR_...,'msg'=> 'ewentualny komunikat ','data'=> array("tu wszystkie dane w postaci tablicy asocjacyjnej")))
   */
  abstract protected function logic();
  /**
   * Po odebraniu tabeli $_REQUEST wykonaj akcje obiektu.
   * Rezultat pracy zostanie zgromadzony w tablicy $data i zmiennych opisujących poprawność wykonania operacji.
   */
  public function action(){
    try{
      $this->logic(); // <- Stąd wyskakują wyjątki generowane przez bazę danych ale gdy logika pracuje w pętli wyjątki powinne być obsłużone dla każdej iteracji indywidualnie
			                // za pomocą catchLogicException(Exceptiono) a wyskakuje tylko wyjątek nie dotyczący jednego wiersza który powinien przerwać pętle
    }  catch (\E $E) {
      $this->catchActionException($E);
    }
  }
  /**
   * Zwraca wynik pracy obiektu w postaci JSON.
   * Jeżeli metoda ABuissnesLogic::action() wygenerowała wyjątek to został on obsłużony i metoda zwraca kod błędu komunikat błędu i tablicę wywołań powowdujących błąd.
   * @return json
   */
  public function getJson(){
		return json_encode(array(
						'success'       => $this->success,
						'code'          => $this->return_code,
						'msg'           => $this->return_msg,
						'data'          => $this->dataOut,
						'countTotal'    => $this->countTodalOut,
						'countFiltered' => $this->countFilteredOut,
						'count'         => $this->countOut,
						'err'           => $this->dataOutErr
						));
  }
	/**
	 * Obsługuje wyjątki nie związane z zastosowaniem logiki na pojedynczym zestawie danych, rzucone podczas wykonywania logiki na pojedynczym zestawie danych ale
	 * nie związane z implementacją logiki na danych.
	 *
	 * @param \Exception $E
	 * @throws Exception | PDOException | E
	 * @done 2014-10-30 Przepudowa obsługi wyjątków
	 * @todo 2014-10-30 Przenieść do beta\BusinessLogic
	 */
  final protected function catchActionException($E){
		$this->success = false;
		if($this->return_code === \pjpl\e\a\E::OK ){
			$this->return_code = \pjpl\e\a\E::UNKNOWN;
			// $this->return_code może zawierać już kod szczegółowiej opisujący błąd niż jako E::ERR_UNKNOWN
			// więc nie można modyfikować tego atrybutu gdy nie wskazuje na E::OK.
			// Konkretną wartość kodu błędy należy nadawać w metodach które zostały wyspecjalizowane
			// do obsługi tego błędu
		}
		if ( is_a($E, 'E') ) {
			$this->catchE($E);
		} else {
			$this->catchException($E);
		}
  }
	/**
	 * Obsługuje wyjątek zgłaszany podczas wykonywania pojedynczego cyklu przetwarzania danych wejściowych - jednego kompletu danych wejściowych.
	 *
	 * Jeżeli nie jest nadpisana to domyślnie wyrzuca ten sam wyjątek który otrzymała i sytuacja wyjątkowa przenosi się na cały zestaw danych
	 * wymuszając konieczność obsłużenia przez blok odpowiedzialny za cały blok.<br>
	 * Pokrywająć metodę można reagować na błędy w poszczególnych rekordach co nie zaburzy przetwarzania pozostałych zestawów danych
	 * @param \Exception $E
	 * @throws \Exception
	 * @done 2014-10-30 Przepudowa obsługi wyjątków
	 * @todo 2014-10-30 Zaimplementować do beta\BusinessLogic
	 */
	final protected function catchLogicException(\Exception $E){
		$this->success = false;
		if(is_a($E, '\pjpl\e\a\E')){
			$this->dataOutErr[$this->dataOutKey] = [
							'code' => $E->getExceptionCode(),
							'name' => $E->getExceptionName(),
							'msg' => $E->getMessage()
			];
			switch (get_class($E)){
				case 'pjpl\e\db\ForeignKey':
					$this->catchForeignKey($E);
					break;
				case 'pjpl\e\db\NotUnique':
					$this->catchNotUnique($E);
					break;
				case 'pjpl\e\db\NoRecord':
					$this->catchNoRecord($E);
					break;
				case '\pjpl\e\db\EDBGeneral':
					$this->catchDBGeneral($E);
					break;
				default :
					throw $E;
			}
		}else{
			// Zgłoszony wyjątek nie dotyczy pracy z jedną encja danych wejściowych.
			// Wygląda na problem uniemożliwiający pracę z całym zbiorem encji i należy go ponownie
			// rzucić by trafił do metody $this->catchActionException() gdzie znajduje się globalna
			// obsługa wyjątków dla tego obiektu
			throw $E;
		}
	}
	/**
	 * @param \pjpl\e\a\E $E
	 * @throws \pjpl\e\a\E
	 * @done 2014-10-30 Przebudowa obsługi wyjątków
	 */
	final protected function catchE(\pjpl\e\a\E $E){
		$this->return_code = $E->getExceptionCode();
		$this->return_msg = $E->getMessage();
	}
	/**
	 * @param \Exception $E
	 * @done 2014-10-30 Przebudowa obsługi wyjątków
	 */
	protected function catchException(\Exception $E){
		$this->return_code = \pjpl\e\a\E::UNKNOWN;
		$this->return_msg = $E->getMessage();
	}

	/**
	 * @param \pjpl\e\db\General $DBGeneral
	 * @done 2014-10-30 Przebudowa obsługi wyjątków
	 */
	protected function catchDBGeneral(\pjpl\e\db\General $DBGeneral){
		$this->return_code = $DBGeneral->getExceptionCode();
		$this->return_msg = $DBGeneral->getMessage();
	}
	/**
	 * @param \pjpl\e\db\ForeignKey $E
	 * @done 2014-10-30 Przebudowa obsługi wyjątków
	 */
	protected function catchForeignKey(\pjpl\e\db\ForeignKey $E){
	}
	/**
	 * @param \pjpl\e\db\NotUnique $E
	 * @done 2014-10-30 Przebudowa obsługi wyjątków
	 */
	protected function catchNotUnique(\pjpl\e\db\NotUnique $E){
  }
  public function successStr(){
    if($this->success){
      return 'true';
    }else{
      return 'false';
    }
  }
	/**
	 * Przetwarza tabelę filtrowania przesłaną od klienta ExtJS.
	 * @param array $filter
	 * @return array
	 */
	protected function reformatExtJSFilter(&$filter){
		$new_filter = array();
		if(isset($filter) && ! empty($filter) ){
			$in = json_decode($filter,TRUE);
			foreach ($in as $key => $value) {
				$expression = [];
				switch($value['operator']){
					case 'gt':
						$expression['operator'] = '>';
						break;
					case 'lt':
						$expression['operator'] = '<';
						break;
					case 'eq':
						$expression['operator'] = '=';
						break;
					default :
						$expression['operator'] = $value['operator'];
						break;
				}
				$expression['attribute'] = $this->Firewall->string($value['property']);
				$expression['value'] = $this->Firewall->string($value['value']);
				$new_filter[] = $expression;
			}
		}
		return $new_filter;
	}
  /**
   * @var DB
   */
  protected $DB = null;
  /**
   * @var Firewall
   */
  protected $Firewall = null;
  /**
   * Dane przesłane do przetwarzania
   * @var array
   */
  protected $dataIn;
	/**
	 * Obecnie przetwarzana pozycja w tabeli danych wejściowych.
	 * Zastosowanie poniższego sposobu pracy z danymi wejściowymi pozwala odnaleźć miejsce podczas przetwarzania którego rzucony został wyjątek
	 * <code>
	 *	foreach($this->dataIn as $this->dataInKey => $value ){
	 *		// ...
	 *	}
	 * </code>
	 * @var mixed
	 */
	protected $dataInKey;
	/**
   * Dane utworzone podczas przetwarzania
   * @var array
   */
  protected $dataOut;
	/**
	 * Ilość wczytanych rekordów
	 * @var int
	 */
	protected $countOut = 0;
	/**
	 * Ilość wszystkich rekordów w tabeli bazy danych
	 * @var int
	 */
	protected $countTodalOut = 0;
	/**
	 * Ilość rekordów spełniających warunki filtrowania
	 * @var int
	 */
	protected $countFilteredOut = 0;
	/**
	 * Obecnie przetwarzana pozycja w tabeli danych wyjściowych.
	 * Zastosowanie poniżeszgo sposobu pracy z danymi wyjściowymi pozwala odnaleźć miejsce podczas przetwarzania którego rzucony został wyjątek
	 * <code>
	 *	foreach($this->dataOut as $this->dataOutKey => $value ){
	 *		// ...
	 *	}
	 * </code>
	 * @var mixed
	 */
	protected $dataOutKey;
	/**
	 * Tablica zawiera opis błędów, które wystąpiły podczas przetwarzania danych wejściowych. Doklejana jest do jsona odpowiedź jako tablica err.
	 *
	 * Klucz tablicy odpowiada kluczowi dataIn i dataOut przetwarzanych danych
	 *
	 * @var array
	 */
	protected $dataOutErr = [];
  /**
	 * Jeżeli $success == true i $return_code == \pjpl\e\a\E::OK to wszystkie dane przetworzono poprawnie.
	 * Jeżeli $success == false i $return_code == \pjpl\e\a\E::OK to tylko część danych wejściowych nie została przetworzona
	 *		a szczczegóły błędu dla każdego kompletu danych wejściowych zapisane są w tablicy wyjściowej $err pod tym samym
	 *		kluczem pod jakim znajdują się dane wejściowe w tablicy dataIn
	 * Jeżeli $success == false i $return_code != \pjpl\e\a\E:OK to cała operacja przebiegła nie poprawnie a $return_code określa kod błędu
   * @var boolean
   */
  protected $success = true;
  /**
	 * Kod do całej operacji - na wszystkich danych wejściowych.
	 * Używać tak jak w opisie do zmiennej $success.
	 * Po uruchomieniu metody catchActionException $return_code otrzymuje wartość \pjpl\e\a\E::UNKNOWN
	 * Konkretna wartość może być ustalana w metodach obsługujących konkretny typ błędu
   * @var int
   */
  protected $return_code = \pjpl\e\a\E::OK;
  /**
	 * Komunikat do całej operacji - na wszystkich danych wejściowych
   * @var string
   */
  protected $return_msg = "OK";
}
?>
