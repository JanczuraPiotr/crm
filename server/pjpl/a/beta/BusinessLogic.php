<?php
namespace pjpl\a\beta;
use pjpl\db\DB;
use pjpl\Firewall;
use pjpl\FirewallEx;
use pjpl\e\a\E;

/**
 * Realizacja algorytmu biznesowego z interfejsem dla wywołań wewnętrznych aplikacji i wywołań zewnętrznych.
 *
 * Jądrem klasy jest metoda _algorithm() która realizuje algorytm biznesowy. Metoda wywołwana jest przez metody:
 * algorithm() oraz action(). Klasa przewidziana jest do współpracy z klientem ExtJS, który domyślnie wysyła dane
 * w formie tablicy rekordów, więc metoda action() wykonuje _algorithm() w pętli dla każdego rekordu z zestawu
 * danych wejściowych. Metoda algorithm() wykonuje operację na jednym zestawie, kolejne jej wywołanie pracuje
 * na kolejnych rekordach do napotkania końca danych wejściowych
 *
 * Paczki danych mogą być przesyłane spoza aplikacji jak i z innej klasy biznesowej tej aplikacji.
 * Dane nadsyłane spoza aplikacji muszą być filtrowane. Dane z innych klas aplikacji traktowane jako bezpieczne,
 * przefiltrowane przez inne klasy i pobierane są bez filtrowania. Za prawidłowe pobranie danych odpowiadają metody
 * internalParams(array &$set_params), która kopiuje dane wejściowe bez ich filtrowania a metoda externalParams(array &$set_params)
 * wywoływana jest dla danych zewnętrznych więc musi być zapewniona kontrola danych. Metody wymagają zdefiniowania
 * metod (wsadów) w postaci _internalParams(array &$set_params) i  _externalParams(array &$set_params).
 *
 * Każde przekazanie danych do obiektu wywołuje metodę _init() która powinna wykonać operacje inicjujące obiekt.
 * Inicjacja może sugerować się odebranymi danymi ale głównym zadaniem metody _init() jest inicjacja obiektów
 * pomocniczych, których utworzenie jest sensowne dopiero po odbieraniu poprawnych danych wejściowych. Domyślnie
 * metoda dołącza tablicę danych przeznaczonych do przetwarzania do tablicy danych wejściowych:
 * <code>
 *	// Domyślna treść metody _init()
 *	$this->dataIn = &$this->input_arguments;
 * </code>
 * Tworząc nowy obiekt który który musi wykonać operacje inicjujące dodaj ten kod albo we własnym zakresie utwórz
 * tablicę $this->dataIn :
 * <code>
 *	// Pokrywanie metody w klasie pochodnej:
 *	protected function _init(){
 *		// Tu możesz utworzyć obiekty wymagane do wykonania właściwego algorytmu klasy biznesowej
 *		// Tu możesz połączyć dataIn z input_arguments jak pokazano wyżej albo utworzyć dataIn ręcznie:
 *		$this->dataIn = array();
 *		foreach($this->input_arguments ; $key => $value ){
 *			// coś tam liczę i ...
 *			$this->dataIn[] = jakas_funkcja_tworząca_parametry()
 *		}
 *	}
 * </code>
 * Nadpisując metodę _init() można na podstawie $this->input_arguments utworzyć właściwą tablicę $this->dataIn.
 * Po przekazaniu parametrów do obiektu można wykonać logikę poprzez wywołanie metody action() po której można
 * wykonać getJson(). Na podrzeby wywołania wewnętrznego w miejsce getJson() wywołać getDataOut().
 *
 * Wszystkie te opreacje, można wykonać za pomocą jednego wywołania medod externalCall(array &$set_params) lub
 * intenalCall(array &$set_params).
 *
 * Klasa na potrzeby aplikacji może być zdefiniowana w poniższy sposób :
 *	1.	Odziedzicz klasę.
 *	2.	Metoda _internalParams(&$set_params) traktuje $set_params jako tablicę zawierającą tablicę parametrów
 *			przeznaczonych dla jednego wywołania metody _algorithm() i musi przejrzeć cały set i dla każdego przenieść
 *			params do dataIn[].
 *	3.	Metoda _externalParams(&$set_params) traktuje $set_params jako tablicę zawierającą tablicę paramterów
 *			przeznaczonych dla jednego wywołania metody _algorithm() i musi przejrzeć cały set i dla każdego przenieść
 *			params do dataIn[] wykonując na każdym elemencie tablicy parametrów testy bezpieczeństwa za pomocą
 *			obiektu Firewall
 *	4.	Metoda _init() może pozostać pusta ale można w niej wykonać inicjację obiektów, których tworzenie przed
 *			skompletowaniem danych do przetwarzania była by nieekonomiczna. Docelowo _init() będzie wywoływana
 *			tylko gdy _externalParams() i _internalPrarams() nie zgłosiły błędu
 *	5.	Metoda _action() może być pokrywana tylko gdy "bezmyślne" wykonanie _algorithm() dla całego zestawu danych
 *			wejściowych musi być zmienione.
 *	6.	Metoda _algorithm($in = null ) Wykonuje właściwą logikę biznesową dla jednego kompletu parametrów i musi zwrócić
 *			odpowiedź w identycznym formacie/typie dla każdego wywołania. Odpowiedź trafia do dataOut[] z której podczas wywołania
 *			getJson() konwertowana jest na jsona wysyłanego do klienta więc gdy do dataOut[] trafiają obiekty należy zapewnić
 *			konwersję do string w metodzie getJson() poprzez np nadpisanie metody.
 *
 * Wykonanie logiki.
 *	1.	Utwórz obiekt
 *	2.	Gdy logika ma przetworzyć dane z wnętrza aplikacji wywołanie może przebiegać w poniższy sposób :
 *			<code>
 *				$set_params = JakisObiektWygenerowalZestawDanych;
 *				$Logika = new MyBusiness(DB::getInstance(), Firewall::getInstance());
 *				$tablica = $Logika->internalCall($set_params);
 *				foreach($tablica in $k => $v){
 *					analiza();
 *				}
 *			</code>
 *			lub:
 *			<code>
 *				// ... jw
 *				$params = true;
 *				while($result = $Logika->algorithm($params) ){
 *					// W $params są dane wejściowe
 *					// W $result są dane wyjściowe
 *					analiza($result);
 *				}
 *			</code>
 *	3.	Wykonanie logiki w odpowiedzi na wywołanie zewnętrzne:
 *			<code>
 *				$Logika = new BusinessLogic(DB::getInstance(), Firewall::getInstance());
 *				$json = $Logika->externalCall($_REQUEST);
 *				echo $json;
 *			</code>
 *			Również można wykonać algorithm();
 *	5.	Jeżeli uruchomenie logiki nastąpiło w opowiedzie na wywołanie ajaxowe zakończ pracę wykonując : echo BusinessLogic::getJson()
 *	6.	Wszystkie kroki można wykonać jednym poleceniem BusinessLogic::runForInside() lub BusinessLogic:runForRequest()
 *
 * @package pjpl
 * @subpackage logic
 * @author Piotr Janczura <piotr@janczura.pl>
 * @done 2014-12-20
 * @doc 2014-09-17
 * @todo zaplanować obsługę wyjątków.
 * @todo wymusić operowanie na encjach zamiast tablicach atrybutów
 * @todo Klasa powinna mieć wydzielone funkcjonalności do klasy InputControler.
 */
abstract class BusinessLogic{
	/**
	 * @param DB $DB
	 * @param Firewall $Firewall
	 * @done 2014-09-16
	 */
  function __construct($DB,$Firewall){
    $this->arguments = array();
    $this->dataOut = array();
    $this->DB = $DB;
    $this->Firewall = $Firewall;
  }

	/**
	 * Wykona algorytm na jednej pozycji tablicy danych wejściowych, na który wskazuje wewnętrzny wskaźnik tablicy.
	 *
	 * Metoda może być wywoływana wielokrotnie. Po każdym wywołaniu przesunie się do następnego zestawu danych wejściowych zapisanych w dataIn.
	 * Gdy osiągnie koniec tablicy zwróci null.
	 * @param array $in - Nazwa tablicy zawierającej zestaw danych wejściowych dla którego wygenerowano odpowiedz. Nie wymagane.
	 * @return mixed Gdy nie było danej wejściowej zwraca NULL
	 * @done 2014-09-16
	 */
	public function algorithm(& $in = NULL){
		$ret = NULL;
		if( ( $arguments = current($this->dataPrepared) ) ){
			$key = key($this->dataPrepared);
			$ret = $this->dataOut[$key] = $this->_algorithm($arguments);
			if(isset($in)){
				$in = array('key'=>$key, 'arguments'=>$arguments);
			}
			next($this->dataPrepared);
		}
		return $ret;
	}
	/**
	 * Wykonuje Operacja na jednym zestawie danych wejściowych - zapisanych na jednej pozycji w tablicy wejściowej dataIn[]
	 * @return mixed
	 * @done 2014-09-12
	 */
	abstract protected function _algorithm(& $arguments);

	/**
	 * Wykona wszystkie operacje logiki dla wywołania wewnątrz aplikacji.
	 *
	 * Pracuje na wszystkich encjach zebranych w tablicy wejściowej dataIn. przeznaczona do przetwarzania danych nadesłanych w tablicy.
	 *
	 * @param array $arguments tablica tablic asocjacyjnych w których kluczem jest nazwa zmiennej a wartością wartość tej zmiennej
	 * @return array Tablica wyników operacji na tablicy danych wejściowych -> dataOut[key][params] << dataPrepared[key][params]
	 * @confitm 2014-09-16
	 */
	final public function internalCall(& $arguments){
		$this->internalArguments($arguments);
		$this->action();
		return $this->getDataOut();
	}
	/**
	 * Wykona wszystkie operacje dla wywołania z zewnątrz aplikacji.
	 *
	 * Pracuje na wszystkich encjach zebranych w tablicy wejściowej dataIn. Przeznaczona do przetwarzania danych nadesłanych w tablicy.
	 *
	 * Pobierze tablicę _REQUEST, utworzy obiekt i zwróci jsona.
	 * @param array $arguments tablica tablic asocjacyjnych w których kluczem jest nazwa zmiennej a wartością wartość tej zmiennej
	 * @return json
	 * @done 2014-09-16
	 */
	final public function externalCall(& $arguments){
		$this->externalArguments($arguments);
		$this->action();
		return $this->getJson();
	}
	/**
	 * Przekazuje parametry podczas wywoływania logiki przez inny obiekt wewnątrz skryptu.
	 *
	 * Od metody externalParams różni się tym, że nie wykonuje kontroli danych wejściowych pod względem bezpieczeństwa.
	 * @param array $arguments tablica tablic asocjacyjnych w których kluczem jest nazwa zmiennej a wartością wartość tej zmiennej
	 * @done 2014-09-16
	 */
	public function internalArguments(& $arguments){
		// @todo w tym miejscu : $this->input_arguments = array();
		$this->_internalArguments($arguments);
		$this->_init();
	}
	/**
   * Przekaż tablicę np: $_REQUEST do tej metody i tu dokonaj jej rozbioru na podstawowe dane i na ich podstawie utwórz parametry wejściowe.
	 *
	 * @param array $arguments tablica tablic asocjacyjnych w których kluczem jest nazwa zmiennej a wartością wartość tej zmiennej
	 * @done 2014-09-16
   */
	public function externalArguments(& $arguments){
		// @todo w tym miejscu : $this->input_arguments = array();
		$this->_externalArguments($arguments);
		$this->_init();
	}
	/**
	 * Inicjuje obiekt przygotowując go do wykonania logiki zapisanej.
	 *
	 * Powinna być wywołana po pobraniu danych wejściowych
	 * Jeżeli inicjacja nie powiedzie się, musi zwrócić false.
	 * @return bool
	 * @done 2014-09-16
	 */
	public function init(){
		$this->_init();
	}
	abstract protected function _internalArguments(& $arguments);
  abstract protected function _externalArguments(& $arguments);
	/**
	 * Metoda przygotowuje obiekt do wykonania algorytmu po odebraniu danych wejściowych. Tworzy wymagane obiekty, obrabia dane wejściowe.
	 *
	 * Domyślnie "łączy" dataPrepares do arguments ponieważ w większości przypadków przetwarzanie odbywa się bezpośrednio
	 * na nadesłanych danych - wystarczy, że będą przefiltrowane. Jeżeli dane wejściowe muszą być zmodyfikowane przed
	 * uruchomieniem algorytmu należy w tej metodzie wykonać te operacje odpowiednio tworząc tablicę dataPrepared[] na podstawie
	 * tablicy arguments. Jeżeli dziedziczysz _init() to musisz zapewnić że dataPrepared[] zawiera dane do przetworzenia.
	 * Jeżeli inicjacja nie powiedzie się musi zwrócić false.
	 * @return bool
	 */
	protected function _init(){
		$this->dataPrepared = & $this->arguments;
	}
	/**
	 * Wykonuje logikę biznesową dla całego zestawu danych wejściowych.
	 *
	 * Domyślnie przeglądana jest tablica $this->dataPrepared[] i do przetwarzania wykorzystywane są kolejno
	 * wszystkie elementy tablicy, bez konieczności ujęcia przetwarzania w transakcję na bazie danych.
	 * Zakładam pokrycie tej metody tylko gdy przetwarzanie wymaga użycia transakcji lub gdy _algorithm()
	 * ma być wykonywany na innych danych niż $this->dataPrepared[].
	 * Gdy, każde wywołanie _algorithm() ma być objęte indywidualną transakcją należy to uwzględnić
	 * wewnątrz metody _algorithm()
	 * @done 2014-09-13
   */
  protected function _action(){
		foreach ($this->dataPrepared as $key => $arguments) {
			$this->dataOut[$key] = $this->_algorithm($arguments);
		}
	}
  /**
	 * Wykonuje logikę biznesową dla wszystkich danych wejściowych.
	 *
	 * Tylko wywołuje _action()
	 * @done 2014-09-13
   */
  final public function action(){
		$this->_action();
  }
	public function catchException(\Exception $E){
		$this->return_code = $E->getCode();
		$this->return_msg = $E->getMessage();
	}

	/**
	 * Dane jaki obiekt otrzymał do przetworzenia
	 * @return array
	 * @done 2014-09-17
	 */
	public function getArguments(){
		return $this->arguments;
	}
	/**
   * Zwraca wynik pracy obiektu w postaci JSON.
	 *
   * Docelowo BusinessLogic będzie obsługiwać wszystkie wyjątki i zamieniać je na kod błędu w ret
	 * i jego opis w msg a success będzie true tylko gdy wszystko poszło ok.
	 * W obecnym stanie należy w każdej dziedziczonej klasie zadbać modyfikowanie powyższych wartości.
   * @return json
	 * @version 2014-09-13
   */
  public function getJson(){
    return json_encode([
						'success'       => $this->success,
						'code'          => $this->return_code,
						'msg'           => $this->return_msg,
						'data'          => $this->dataOut,
						'countTotal'    => $this->countTodalOut,
						'countFiltered' => $this->countFilteredOut,
						'count'         => $this->countOut,
						'err'           => $this->dataOutErr
						]);
  }
	/**
	 * Domyślnie zwraca tablicę danych wyjściowych.
	 *
	 * @return array
	 * @done 2014-09-13
	 */
	public function getDataOut(){
		return $this->dataOut;
	}
	/**
	 * Komunikat o ewentualmym błędzie błędzie. Ma sens gdy getSuccess() == false
	 * @return string
	 * @done 2014-09-13
	 */
	public function getOutMsg(){
		return $this->return_msg;
	}
	/**
	 * Kod komunikatu o błędzie. Ma sens gdy getSuccess() == false.
	 * @return int
	 * @done 2014-09-13
	 */
	public function getOutCode(){
		return $this->return_code;
	}
	/**
	 * Przetwarza tabelę filtrowania przesłaną od klienta ExtJS.
	 * @param array $_request Referencja do $_REQUEST
	 * @return array
	 */
	protected function reformatExtJSFilter(&$_request){
		$filter = array();
		if(isset($_request['filter']) && ! empty($_request['filter']) ){
			$in = json_decode($_request['filter'],TRUE);
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
				$filter[] = $expression;
			}
		}
		return $filter;
	}
  /**
   * @var \pjpl\db\DB
   */
  protected $DB = null;
  /**
   * @var \Firewall
   */
  protected $Firewall = null;
  /**
   * Dane przesłane do przetwarzania
   * @var array
   */
  protected $arguments;
	/**
	 * Dane przygotowane do przetwarzania przez metodę _init();
	 * Metoda _algorythm() wywoływana jest dla każdego elementu tej tablicy.
	 * @var array
	 */
	protected $dataPrepared;
	/**
   * Dane utworzone podczas przetwarzania.
	 * Metoda _algorithm() umieszcza tu wyniki swojej pracy.
   * @var array
   */
  protected $dataOut;
	/**
	 * Tablica zawiera opis błędów, które wystąpiły podczas przetwarzania danych wejściowych. Doklejana jest do jsona odpowiedź jako tablica err.
	 *
	 * Klucz tablicy odpowiada kluczowi dataIn i dataOut przetwarzanych danych
	 *
	 * @var array
	 */
	protected $dataOutErr = [];
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
   * Gdy w trakcie przetwarzania wystąpi błąd ustawić na false a następnie podczas tworzenia odpowiedzi dla klienta należy odnaleźć te pozycje
   * w $this->dataOut które oznaczono jako przetworzone z błędem i odpowiednio sformatować komunikat.
   * @var boolean
   */
  protected $success = true;
	/**
	 * Kod o wykonaniu logiki przeznaczony do zwrócenia wewnątrz jsona.
   * @type int
   */
  protected $return_code = E::OK;
  /**
	 * Komunikat wykonaniu logiki przeznaczony do zwrócenia wewnątrz jsona
   * @type string
   */
  protected $return_msg = 'OK';
}
?>
