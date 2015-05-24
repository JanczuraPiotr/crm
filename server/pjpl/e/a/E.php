<?php
namespace pjpl\e\a;
/**
 * Wyjątek bazowy dla wszystkich wyjątków rozwijanych w pjpl.
 * @package pjpl
 * @subpackage exceptions
 * @author Piotr Janczura <piotr@janczura.pl>
 * @confirm 2014-09-17
 * @todo logowanie informacji o błędach do bazy danych
 */
abstract class E extends \Exception{
	const OK                 = 1;
	const UNKNOWN            = 0;
	const NOT_LOGIN          = -1;
	// Przestrzen od -1 do -99 na kody błędów nie obsługiwanych wyjątkami

	const EXCEPTION          = -100;
	const EGENERAL           = -100;
	const EARRAYINDEX        = -101;
	/**
	 * Nie znaleziono pliku
	 */
	const EFILEFIND          = -102;
	/**
	 * Nie można pisać do pliku
	 */
	const EFILEWRITE         = -103;
	/**
	 * Nie udało się otworzyć istniejącego pliku
	 */
	const EFILEREAD          = -104;
	const ENORECORD          = -105;
	const ECONNECT           = -106;
	const EBADIN             = -107;
	const ENOTSET            = -108;
	const EBADFORMAT         = -109;
	const ECOHERENT          = -110;
	const ENOENCJA           = -111;
	const ENONEXISTATTRIBUTE = -112;

	const EDB                = -200;
	const EDB_GENERAL        = -200;
	const EDB_CONNECT        = -201;
	const EDB_INCOMPLETELY   = -202;
	const EDB_INSERT         = -203;
	const EDB_UPDATE         = -204;
	const EDB_UPDATE_UPDATED = -205;
	const EDB_SELECT         = -206;
	const EDB_DELETE         = -207;
	const EDB_FOREIGNKEY     = -208;
	const EDB_NOTUNIQUE      = -209;
	const EDB_OPERATION_NULL = -210;
  /**
   * Nazwa klasy z której rzucono wyjątek
   */
  private $class_name=null;
  /**
   * Nazwa funkcji z której rzucono wyjątek
   */
  private $function_name=null;
	/**
	 * @param string $class_name Nazwa klasy w której zgłoszono wyjątek
	 * @param string $function_name Nazwa wunkcji w której zgłoszono wyjątek
	 * @param string $message Komunikat
	 */
//	public function __construct(string $class_name, string $function_name, string $message = '') {
	public function __construct($class_name, $function_name, $message = '') {
		$this->class_name = ( $class_name !== '' ? $class_name : 'Function' );
		$this->function_name = $function_name;
		parent::__construct($message, static::code());
	}
	/**
	 * Własny kod błędu związany klasą rzucającą wyjątek. Będzie zwracany podczas wywoływania metody getCode()
	 *
	 * W klasach dziedziconych należy utworzyć tę metodę by zwracała kod błędu odpowiedni dla tego wyjątku. Tą wartością będzie inicjowana wartość code klasy bazowej.
	 *
	 * <code>
	 *	public function code(){
	 *		return E::ERR_NAZWA_KODU_BLEDU;
	 *	}
	 * </code>
	 * @return int
	 */
	abstract protected function code();
	/**
	 * Nazwa wyjątku. Przydaje się np w logach lub komunikatach json. Nazwa będzie zwracana przez metodę getName()
	 * Ze względu na przekazywane komunikatów o błędach do innych systemów dobrze jest zachować spójność kodów błędów i ich tekstowej reprezentacji.
	 * Dla tego zakładam że metoda będzie zwracać nazwę kodu użytego w metodzie getCode();
	 * Pokryć w każdej klasie by zwracany ciąg odpowiadał nazwie tej klasy
	 * <code>
	 *	public function getName(){
	 *		return 'ERR_NAZWA_KODU_BLEDU'; // Uwaga! Zwracamy string
	 *	}
	 * </code>
	 * @return string
	 */
	abstract protected function name();
	/**
	 * Kod błędu związanego z wyjątkiem
	 * @return int
	 */
	public function getExceptionCode(){
		return $this->getCode();
	}
	/**
	 * Nazwa tego wyjątku
	 * @return string
	 */
	public function getExceptionName(){
		return $this->name();
	}
	/**
	 * Nazwa klasy z której rzucono wyjątek
	 * @return string
	 */
	public function getClassName(){
		return $this->class_name;
	}
	/**
	 * Nazwa funkcji z której rzucono wyjątek
	 * @return string
	 */
	public function getFunctionName(){
		return $this->function_name;
	}
	/**
	 * Zrzut do tablicy wszystkich znanych parametrów wyjątku
	 * @return array
	 */
	public function toArray(){
    return array(
						'class'		=>	$this->getClassName(),
						'method'	=>	$this->getFunctionName(),
						'name'		=>	$this->getExceptionName(),
						'code'		=>	$this->getCode(),
						'msg'			=>	$this->getMessage(),
						'file'		=>	$this->getFile(),
						'line'		=>	$this->getLine()
		);
  }
}
