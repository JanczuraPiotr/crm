<?php
namespace pjpl\depreciate;
/**
 * @package pl.janczura.piotr.php
 * @subpackage basic library
 * @author <piotr@janczura.pl>
 */
//------------------------------------------------------------------------------
// 2012-01-06
// 2013-10-15 - ENumberFormat
// 2013-11-06 - EDBUpdateUpdated
//------------------------------------------------------------------------------
if(!defined('OK')){
  define('OK'                     ,1);
}
define('THROW_NOSAVE'             ,0);
define('THROW_SAVE'               ,1); // Domyślna wartość parametru $insert przekazywana w konstruktorze wyjątku. Jeżeli 1 to włączone jest zapisanie info o tym wyjątki, THROW_SAVE musi być 1
define('THROW_SAVE_FOR_NOTSET'    ,0);
define('THROW_SAVE_FOR_NOREC'     ,0);
define('ERR_UNKNOWN'              ,OK-1);
define('ERR_NOT_LOGIN'            ,ERR_UNKNOWN-1);
define('ERR_AJAX_PARAM'           ,ERR_NOT_LOGIN-1);   // Nie kompletne parametry przekazane w wywołaniu
define('ERR_AJAX_ASYNC'           ,ERR_AJAX_PARAM-1);  // Prówbowano użyć obiektu Ajax jako synchronicznego gdy komunikacja została zainicjowana jako asynchroniczna
define('ERR_SERIALIZE_CREATE'     ,ERR_AJAX_ASYNC-1);
define('ERR_SERIALIZE_READ'       ,ERR_SERIALIZE_CREATE-1);
define('ERR_TYPEOF'               ,ERR_SERIALIZE_READ-1);
define('ERR_ENOTIMPL'             ,ERR_TYPEOF-1);

define('ERR_EXCEPTION'            ,-100);
define('ERR_EARRAYINDEX'          ,ERR_EXCEPTION-1);//-101
define('ERR_EFILE'                ,ERR_EARRAYINDEX-1);//-102
define('ERR_EFILEW'               ,ERR_EFILE-1);//-103
define('ERR_EFILER'               ,ERR_EFILEW-1);//-104
define('ERR_ENOREC'               ,ERR_EFILER-1);
define('ERR_ECONNECT'             ,ERR_ENOREC-1);
define('ERR_EBADIN'               ,ERR_ECONNECT-1);
define('ERR_ENOTSET'              ,ERR_EBADIN-1);
define('ERR_EBADFORMAT'           ,ERR_ENOTSET-1);
define('ERR_ECOHERENT'            ,ERR_EBADFORMAT-1);

define('ERR_EDB'                  ,-200);
define('ERR_EDB_CONNECT'          ,ERR_EDB-1);
define('ERR_EDB_INCOMPLETELY'     ,ERR_EDB_CONNECT - 1);
define('ERR_EDB_INSERT'           ,ERR_EDB_INCOMPLETELY-1);
define('ERR_EDB_UPDATE'           ,ERR_EDB_INSERT-1);
define('ERR_EDB_UPDATE_UPDATED'   ,ERR_EDB_UPDATE-1);
define('ERR_EDB_SELECT'           ,ERR_EDB_UPDATE_UPDATED-1);
define('ERR_EDB_DELETE'           ,ERR_EDB_SELECT-1);
define('ERR_EDB_FOREIGNKEY'       ,ERR_EDB_DELETE-1);
define('ERR_EDB_NOTUNIQUE'        ,ERR_EDB_FOREIGNKEY-1);
define('ERR_EDB_OPERATION_NULL'   ,ERR_EDB_NOTUNIQUE-1);

define('ERR_PDO'                  ,-300);

// 2014-09-06 Przeniosłęm do pjpl\e\a
abstract class abstractE extends \Exception{
  protected $insert= THROW_NOSAVE;
  public function setInsert($insert=null){
    if($insert === null){
      $this->insert = THROW_NOSAVE;
    }else{
      $this->insert = $insert;
    }
  }
  /**
   * Kod wyjątku.
   * Nie jest kodem błędu generującego wyjątek. Od tego jest getCode().
   */
  abstract public function getKod();
  /**
   * Nazwa wyjątku.
   * Tak jak symboliczna nazwa stałej ale zwracana w postaci napisu
   */
  abstract public function getNazwa();
  /**
   * Zapisuje do bazy danych informacje o rzuconym wyjątku.
   */
  abstract protected function insertDB();
}
// 2014-09-06 Przeniosłem do pjpl\e
class E extends abstractE{
  /**
   * Nazwa klasy do której należą zmienne
   */
  private $klasa=null;
  /**
   * Miejsce stwierdzenia niespójności
   */
  private $funkcja=null;
  /**
   * Wiadomość przygotowana do wyświetlenia dla użytkownika
   * @var string
   */
  private $user_msg='';
  private $throw_save = THROW_NOSAVE;
  /**
   *
   * @param string $klasa - Nazwa klasy w której zgłoszono wyjątek
   * @param string $funkcja - funkcja w której zgłoszono wyjątek
   * @param string $message - wiadomość systemowa o wyjątku
   * @param string $user_msg - wiadomość do wyświetlenia użytkownikowi systemu
   * @param boolean $insert - Czy informacja o wyjątku ma być zapisana do bazy danych
   */
  public function __construct($klasa, $funkcja, $message='', $user_msg='', $throw_save = THROW_NOSAVE ) {
    parent::__construct($message);
    $this->throw_save = $throw_save;
    $this->klasa = $klasa;
    $this->funkcja = $funkcja;
    $this->user_msg = $user_msg;
    if($this->throw_save == THROW_SAVE){
      switch($this->getKod()){
        case ERR_ENOTSET:
          if(THROW_SAVE_FOR_NOTSET){
            $this->insertDB();
          }
          break;
        case ERR_ENOREC:
          if(THROW_SAVE_FOR_NOREC){
            $this->insertDB();
          }
        default:
          $this->insertDB();

      }
    }
  }
  public function getArray(){
    return array(
        'klasa'=>$this->getKlasa(),
        'funkcja'=>$this->getFunkcja(),
        'msg'=>$this->getMsg(),
        'plik'=>$this->getFile(),
        'wiersz'=>$this->getLine(),
        'scieżka'=>$this->getTrace()
        );
  }
  public function getUserMsg(){return $this->user_msg;}
  public function getUserTyp(){
    if(isset($_SESSION['VAR']['USER_TYP'])){
      return $_SESSION['VAR']['USER_TYP'];
    }else{
      return '';
    }
  }
  public function getUserId(){
    if(isset($_SESSION['VAR']['USER_ID'])){
      return $_SESSION['VAR']['USER_ID'];
    }else{
      return '';
    }
  }
  public function getKod(){return ERR_EXCEPTION;}
  public function getNazwa(){return 'ERR_EXCEPTION';}
  public function getKlasa(){return $this->klasa;}
  public function getFunkcja(){return $this->funkcja;}
  public function getMsg(){return 'Plik::'.$this->getFile().' wiersz::'.$this->getLine().' msg::'.$this->getMessage();}
  /**
   * Zapisuje informację o wyjątku zgłoszonym za pomoca tego obiektu
   */
  protected function insertDB(){
    $query=
      "INSERT INTO throw
        (user_typ,user_id,klasa,funkcja,wiersz,kod,code,nazwa,msg)
        VALUE(
          '".$this->getUserTyp()."',
          '".$this->getUserId()."',
          '".$this->getKlasa()."',
          '".$this->getFunkcja()."',
          '".$this->getLine()."',
          '".$this->getKod()."',
          '".$this->getCode()."',
          '".$this->getNazwa()."',
          '".$this->getMsg()."'
        )
      ";
    @mysql_query($query);
  }
}
class EBadType extends E{
  public function __construct($klasa, $funkcja, $oczekiwane = '', $otrzymane = '', $throw_save = THROW_NOSAVE) {
    parent::__construct($klasa, $funkcja, 'Ozekiwano zmiennej typu : '.$oczekiwane.' a otrzymano : '.$otrzymane, '', $throw_save);
  }
}
/**
 * Wyjątek zgłaszany gdy otrzymana zmienna jest inna niż spodziewanego typu liczobowego
 */
class ENumberFormat extends E{
  private $name;
  private $value;
  /**
   * @param string $klasa - Nazwa klasy w której zgłoszono wyjątek
   * @param string $funkcja - funkcja w której zgłoszono wyjątek
   * @param string $name - nazwa zmienej
   * @param string $value - wartość zmiennej
   * @param string $message
   * @param string $user_msg
   * @param string $throw_save
   */
  public function __construct($klasa, $funkcja, $name, $value, $message = 'Wartość nie jest liczbą', $user_msg = 'Podana wartość jest niespodziewanego typu', $throw_save = THROW_NOSAVE) {
    parent::__construct($klasa, $funkcja, $message, $user_msg, $throw_save);
    $this->name = $name;
    $this->value = $value;
  }
  public function getName(){
    return $this->name;
  }
  public function getValue(){
    return $this->value;
  }
}
class EDoubleFormat extends ENumberFormat{
  /**
   * @param string $klasa - Nazwa klasy w której zgłoszono wyjątek
   * @param string $funkcja - funkcja w której zgłoszono wyjątek
   * @param string $name - nazwa zmienej
   * @param string $value - wartość zmiennej
   * @param string $message
   * @param string $user_msg
   * @param string $throw_save
   */
  public function __construct($klasa, $funkcja, $name, $value, $message = 'Wartość nie jest liczbą typu double', $user_msg = 'Podana wartość nie jest liczbą typu double', $throw_save = THROW_NOSAVE) {
    parent::__construct($klasa, $funkcja, $name, $value, $message, $user_msg, $throw_save);
  }
}
class EDecimalFormat extends ENumberFormat{
  /**
   * @param string $klasa - Nazwa klasy w której zgłoszono wyjątek
   * @param string $funkcja - funkcja w której zgłoszono wyjątek
   * @param string $name - nazwa zmienej
   * @param string $value - wartość zmiennej
   * @param string $message
   * @param string $user_msg
   * @param string $throw_save
   */
  public function __construct($klasa, $funkcja, $name, $value, $message = 'Wartość nie jest liczbą typu decimal', $user_msg = 'Podana wartość nie jest liczbą typu decimal', $throw_save = THROW_NOSAVE) {
    parent::__construct($klasa, $funkcja, $name, $value, $message, $user_msg, $throw_save);
  }
}
/**
 * Przekroczono rozmia tablicy
 */
class EArrayIndex extends E{
  var $tablica=null;
  var $otrzymana=null;
  var $dopuszczalna=null;
  /**
   *
   * @param string $klasa - Nazwa klasy w której zgłoszono wyjątek
   * @param string $funkcja - funkcja w której zgłoszono wyjątek
   * @param string $tablica - nazwa tablicy do której odwołano się
   * @param int $otrzymana - wartość indeksu jakim chciano odczytać wartość
   * @param int $dopuszczalna - maksymalna wartość indeksu dla tej tablicy
   * @param string $user_msg - wiadomość do wyświetlenia użytkownikowi systemu
   * @param boolean $insert - Czy informacja o wyjątku ma być zapisana do bazy danych
   */
  public function __construct($klasa,$funkcja,$tablica,$otrzymana,$dopuszczalna,$user_msg='',$insert=THROW_NOSAVE){
    parent::__construct($klasa, $funkcja,$klasa.'::'.$funkcja.'::'.$tablica.' = '.$otrzymana.' dopuszczalna = '.$dopuszczalna,$user_msg,$insert);
    $this->tablica=$tablica;
    $this->otrzymana=$otrzymana;
    $this->dopuszczalna=$dopuszczalna;
  }
  public function getNazwa(){return 'ERR_EARRAYINDEX';}
  public function getKod() {return ERR_EARRAYINDEX;}
  public function getTablica(){return $this->tablica;}
  public function getOtrzymana(){return $this->otrzymana;}
  public function gtDopuszczalna(){return $this->dopuszcalna;}
  public function getArray(){
    return array(
        'klasa'=>$this->getKlasa(),
        'funkcja'=>$this->getFunkcja(),
        'msg'=>$this->getMsg(),
        'tablica'=>$this->getTablica(),
        'wartość otrzymana'=>$this->getOtrzymana(),
        'wartość dopuszczalna'=>$this->getDopuszczalna(),
        'plik'=>$this->getFile(),
        'wiersz'=>$this->getLine(),
        'scieżka'=>$this->getTrace()
        );
  }
}
/**
 * Problem z otwarciem pliku.
 */
class EFile extends E{
  /**
   *
   * @param string $klasa - Nazwa klasy w której zgłoszono wyjątek
   * @param string $funkcja - funkcja w której zgłoszono wyjątek
   * @param string $message - wiadomość systemowa o wyjątku
   * @param string $user_msg - wiadmość do wyświetlenia użytkowanikowi systemu
   * @param boolean $insert - Czy informacjia o wyjątku ma być zapisana do bazy danych
   */
  public function __construct($klasa,$funkcja,$message,$user_msg='',$insert=THROW_NOSAVE){
    parent::__construct($klasa,$funkcja,$message,$user_msg,$insert);
  }
  public function getNazwa(){return 'ERR_EFILE';}
  public function getKod(){return ERR_EFILE; }
  public function getArray(){
    return array(
        'msg'=>$this->getMsg(),
        'plik'=>$this->getFile(),
        'wiersz'=>$this->getLine(),
        'scieżka'=>$this->getTrace()
        );
  }
}
/**
 *  Gdy operacja zapisu pliku lub do pliku się nie powiedzie
 */
class EFileW extends EFile{
  /**
   *
   * @param string $klasa - Nazwa klasy w której zgłoszono wyjątek
   * @param string $funkcja - funkcja w której zgłoszono wyjątek
   * @param string $message - wiadomość systemowa o wyjątku
   * @param string $user_msg - wiadmość do wyświetlenia użytkowanikowi systemu
   * @param boolean $insert - Czy informacjia o wyjątku ma być zapisana do bazy danych
   */
  public function __construct($klasa,$funkcja,$message,$user_msg='',$insert=THROW_NOSAVE){
    parent::__construct($klasa,$funkcja,$message,$user_msg,$insert);
  }
  public function getNazwa(){return 'ERR_EFILEW';}
  public function getKod(){return ERR_EFILEW;}
}
/**
 * Gdy operacja odczytu z pliku nie powiodła się
 */
class EFileR extends EFile{
  /**
   *
   * @param string $klasa - Nazwa klasy w której zgłoszono wyjątek
   * @param string $funkcja - funkcja w której zgłoszono wyjątek
   * @param string $message - wiadomość systemowa o wyjątku
   * @param string $user_msg - wiadmość do wyświetlenia użytkowanikowi systemu
   * @param boolean $insert - Czy informacjia o wyjątku ma być zapisana do bazy danych
   */
  public function __construct($klasa,$funkcja,$message,$user_msg='',$insert=THROW_NOSAVE){
    parent::__construct($klasa,$funkcja,$message,$user_msg,$insert);
  }
  public function getNazwa(){return 'ERR_EFILER';}
  public function getKod(){return ERR_EFILER;}
}
/**
 * Gdy nie odnaleziono rekordu o tabeli $tabela wyszukiwanego wedłóg kolumny
 * $kolumna w której poszukiwano wartości $wartosc
 */
class ENoRec extends E{
  var $tabela;
  var $kolumna;
  var $wartosc;
  /**
   * @param string $klasa - Nazwa klasy w której zgłoszono wyjątek
   * @param string $funkcja - funkcja w której zgłoszono wyjątek
   * @param string $tabela - tabela bazy danych w której dokonywano przeszukania
   * @param string $kolumna - kolumna bazy dancy w której dokonywano przeszukania
   * @param string $wartosc - wartość jakiej szukano w kolumnie
   * @param string $user_msg - wiadmość do wyświetlenia użytkowanikowi systemu
   * @param boolean $insert - Czy informacjia o wyjątku ma być zapisana do bazy danych
   */
  public function __construct($klasa,$funkcja,$tabela,$kolumna,$wartosc,$user_msg='',$insert=THROW_NOSAVE){
    parent::__construct($klasa,$funkcja,'Nie znaleziono rekordu, dla kolumn :: '.$kolumna.' :: o wartościach :: '.$wartosc,$user_msg='',$insert );
    $this->tabela=$tabela;
    $this->kolumna=$kolumna;
    $this->wartosc=$wartosc;
  }
  public function getNazwa() {return 'ERR_ENOREC';}
  public function getKod() {return ERR_ENOREC;}
  public function getTabela(){return $this->tabela;}
  public function getKolumna(){return $this->kolumna;}
  public function getWartosc(){return $this->wartosc;}
  public function getArray(){
    return array(
        'msg'=>$this->getMsg(),
        'tabela'=>$this->getTabela(),
        'kolumna'=>$this->getKolumna(),
        'wartosc'=>$this->getWartosc(),
        'plik'=>$this->getFile(),
        'wiersz'=>$this->getLine(),
        'scieżka'=>$this->getTrace()
        );
  }
}
/**
 * Nie wykonano zadania ze względu na problem z połączeniem do zdalnego zasobu
 */
class EConnect extends E{
  /**
   *
   * @param string $klasa - Nazwa klasy w której zgłoszono wyjątek
   * @param string $funkcja - funkcja w której zgłoszono wyjątek
   * @param string $message - wiadomość systemowa o wyjątku
   * @param string $user_msg - wiadmość do wyświetlenia użytkowanikowi systemu
   * @param boolean $insert - Czy informacjia o wyjątku ma być zapisana do bazy danych
   */
  public function  __construct($klasa,$funkcja,$message,$user_msg='',$insert=THROW_NOSAVE) {
    parent::__construct($klasa,$funkcja,$message,$user_msg='',$insert);
  }
  public function getNazwa(){return 'ERR_ECONNECT';}
  public function getKod(){return ERR_ECONNECT;}
}
/**
 * Jako parametr przekazany do metody podano wartość która nie jest dopuszczalna.
 * Jest to ogólny wyjątek dla sytuacji gdy zmienne wejścowe są nie prawidłowe.
 * Przekazywany gdy nie można precyzyjnie określić co ze zmiennymi jest nie tak.
 */
class EBadIn extends E{
  var $zmienna=null;
  var $wartosc=null;
  /**
   *
   * @param string $klasa - Nazwa klasy w której zgłoszono wyjątek
   * @param string $funkcja - funkcja w której zgłoszono wyjątek
   * @param string $zmienna - nazwa zmiennej wejściowej która otrzymała złą wartość
   * @param string $wartosc - wartość nadana zmiennej
   * @param string $user_msg - wiadmość do wyświetlenia użytkowanikowi systemu
   * @param boolean $insert - Czy informacjia o wyjątku ma być zapisana do bazy danych
   */
  public function __construct($klasa,$funkcja,$zmienna,$wartosc,$user_msg='',$insert=THROW_NOSAVE){
    parent::__construct($klasa,$funkcja,$klasa.'::'.$funkcja.'::'.$zmienna.'::'.$wartosc,$user_msg,$insert);
    $this->zmienna=$zmienna;
    $this->wartosc=$wartosc;
  }
  public function getNazwa() {return 'ERR_EBADIN';}
  public function getKod(){return ERR_EBADIN;}
  public function getZmienna(){return $this->zmienna;}
  public function getWartosc(){return $this->wartosc;}
  public function getArray(){
    return array(
        'klasa'=>$this->getKlasa(),
        'funkcja'=>$this->getFunkcja(),
        'msg'=>$this->getMsg(),
        'zmienna'=>$this->getZmienna(),
        'wartosc'=>$this->getWartosc(),
        'plik'=>$this->getFile(),
        'wiersz'=>$this->getLine(),
        'scieżka'=>$this->getTrace()
        );
  }
}
/**
 * Gdy zmienna jest nie ustawiona/zainicjowana
 */
class ENotSet extends E{
  private $klasa;
  private $pole;
  private $funkcja;
  /**
   *
   * @param string $klasa - Nazwa klasy w której zgłoszono wyjątek
   * @param string $funkcja - funkcja w której zgłoszono wyjątek
   * @param string $pole - nazwa pola obiektu lub zmiennej w funkcji któremu nie nadano żadnej wartości
   * @param string $user_msg - wiadmość do wyświetlenia użytkowanikowi systemu
   * @param boolean $insert - Czy informacjia o wyjątku ma być zapisana do bazy danych
   */
  public function __construct($klasa,$funkcja,$pole,$user_msg='',$insert=THROW_NOSAVE){
    parent::__construct($klasa,$funkcja,'Próba odczytania nie istniejącej zmiennej - '.$klasa.'::'.$funkcja.'::'.$pole,$user_msg,$insert);
    $this->klasa=$klasa;
    $this->pole=$pole;
    $this->funkcja=$funkcja;
  }
  public function getNazwa(){return 'ERR_ENOTSET';}
  public function getKod(){return ERR_ENOTSET;}
  public function getKlasa(){return $this->klasa;}
  public function getPole(){return $this->pole;}
  public function getFunkcja(){return $this->funkcja;}
  public function getArray(){
    return array(
        'klasa'=>$this->getKlasa(),
        'funkcja'=>$this->getFunkcja(),
        'msg'=>$this->getMsg(),
        'pole'=>$this->getPole(),
        'plik'=>$this->getFile(),
        'wiersz'=>$this->getLine(),
        'scieżka'=>$this->getTrace()
        );
  }
}
/**
  * Obiekt otrzymał wartość w nie poprawnym formacie np źle zpisany email,nip...
  */
class EBadFormat extends E{
  var $klasa;  // nazwa obiektu/klasy rzucającej wyjatek
  var $funkcja;
  var $zmienna; // zmienna któremu próbowano nadać złą wartość
  var $wartosc; // wartość jaką przekazano do obiektu
  /**
   *
   * @param string $klasa - Nazwa klasy w której zgłoszono wyjątek
   * @param string $funkcja - funkcja w której zgłoszono wyjątek
   * @param string $pole - nazwa pola obiektu lub zmiennej w funkcji której nadano zły format
   * @param string $wartosc - wartość nadana zmiennej
   * @param string $user_msg - wiadmość do wyświetlenia użytkownikowi systemu
   * @param boolean $insert - Czy informacjia o wyjątku ma być zapisana do bazy danych
   */
  public function __construct($klasa,$funkcja,$pole,$wartosc,$user_msg='',$insert=THROW_NOSAVE){
    parent::__construct($klasa, $funkcja, 'W obiekcie klasy :: '.$klasa.' w funkcji :: '.$funkcja.' stwierdzono złą wartość parametru ::'.$pole.' = '.$wartosc,$user_msg,$insert);
    $this->klasa=$klasa;
    $this->funkcja=$funkcja;
    $this->pole=$pole;
    $this->wartosc=$wartosc;
  }
  public function getNazwa(){return 'ERR_EBADFORMAT';}
  public function getKod(){return ERR_EBADFORMAT;}
  public function getKlasa(){return $this->klasa;}
  public function getFunkcja(){return $this->funkcja;}
  public function getZmienna(){return $this->zmienna;}
  public function getWartosc(){return $this->wartosc;}
  public function getArray(){
    return array(
        'klasa'=>$this->getKlasa(),
        'funkcja'=>$this->getFunkcja(),
        'msg'=>$this->getMsg(),
        'zmienna'=>$this->getZmienna(),
        'wartosc'=>$this->getWartosc(),
        'plik'=>$this->getFile(),
        'wiersz'=>$this->getLine(),
        'scieżka'=>$this->getTrace()
        );
  }
}
/**
 * funkcja/metoda/obiekt - nie zaimplementowana
 */
class ENotImpl extends E{
  var $klasa;   // Nazwa klasy
  var $funkcja;  // metoda klasy/
  /**
   *
   * @param string $klasa - Nazwa klasy w której zgłoszono wyjątek
   * @param string $funkcja - funkcja w której zgłoszono wyjątek
   * @param string $user_msg - wiadmość do wyświetlenia użytkowanikowi systemu
   * @param boolean $insert - Czy informacjia o wyjątku ma być zapisana do bazy danych
   */
  public function __construct($klasa,$funkcja,$user_msg='',$insert=THROW_NOSAVE){
    parent::__construct($klasa, $funkcja, 'Nie zaimplementowano metody :: '.$funkcja.' w klasie :: '.$klasa,$user_msg,$insert);
    $this->klasa=$klasa;
    $this->funkcja=$funkcja;
  }
  public function getNazwa(){return 'ERR_ENOTIMPL';}
  public function getKod(){return ERR_ENOTIMPOL;}
  public function getKlasa(){return $this->klasa;}
  public function getFunkcja(){return $this->funkcja;}
  public function getArray(){
    return array(
        'klasa'=>$this->getKlasa(),
        'funkcja'=>$this->getFunkcja(),
        'msg'=>$this->getMsg(),
        'plik'=>$this->getFile(),
        'wiersz'=>$this->getLine()
        );
  }
}
/**
 * Dane nie są spójne. Zależności między parametrami obiektu są nie logiczne , nie spełniają założeń ...
 */
class ECoherent extends E{
  /**
   * Lista nisepujnych danych
   */
  private $parametry=array();
  /**
   * Jako parametr parametry należy podać tablice asocjacyjną w której kluczem jest nazwa parametru przekazanego do funkcji a jako wartość wartość tego parametru
   * @param string $klasa - Nazwa klasy w której zgłoszono wyjątek
   * @param string $funkcja - funkcja w której zgłoszono wyjątek
   * @param type $parametry - tablica parametrów i ich wartości przekazanych do funkcji array('par1'=>'var1,'par2'=>par2....)
   * @param string $user_msg - wiadmość do wyświetlenia użytkowanikowi systemu
   * @param boolean $insert - Czy informacjia o wyjątku ma być zapisana do bazy danych
   */
  public function __construct($klasa,$funkcja,$parametry,$user_msg='',$insert=THROW_NOSAVE){
    parent::__construct($klasa,$funkcja,'W obiekcje klasy :: '.$klasa.' w funkcji :: '.$funkcja.' stwierdzono nie spójność danych :: '.json_encode($parametry),$user_msg,$insert);
    $this->parametry=$parametry;
  }
  public function getNazwa(){return 'ERR_ECOHERENT';}
  public function getKod() {return ERR_ECOHERENT;}
  public function getKlasa(){return $this->klasa;}
  public function getFunkcja(){return $this->funkcja;}
  public function getParametry(){return $this->parametry;}
  public function getArray(){
    return array(
        'klasa'=>$this->getKlasa(),
        'funkcja'=>$this->getFunkcja(),
        'msg'=>$this->getMsg(),
        'parametry'=>$this->getParametry(),
        'plik'=>$this->getFile(),
        'wiersz'=>$this->getLine(),
        'scieżka'=>$this->getTrace()
        );
  }
}
/**
 * Wyjątki powstałe po wykonaniu operacji na bazie danych
 */
// 2014-09-06 Przeniosłęm do pjpl\e
class EDB extends E{
  private $query;
  private $msg;
  private $err;
  /**
   *
   * @param string $klasa - Nazwa klasy w której zgłoszono wyjątek
   * @param string $funkcja - funkcja w której zgłoszono wyjątek
   * @param string $query - zapytanie które wygnenerowało wyjątek
   * @param string $msg - myquery_error();
   * @param int $err - mysql_errno()
   * @param string $user_msg - wiadmość do wyświetlenia użytkowanikowi systemu
   * @param boolean $insert - Czy informacja o wyjątku ma być zapisana do bazy danych
   */
  public function  __construct($klasa,$funkcja,$query,$msg,$err=-1,$user_msg='',$insert=THROW_NOSAVE) {
    parent::__construct($klasa,$funkcja,'Zapytanie::'.$query.' Spowodowało błąd o komunikacie : '.$msg.' i kodzie : '.$err,$user_msg,$insert);
    $this->query=$query;
    $this->msg=$msg;
    $this->err=$err;
  }
  public function getNazwa(){return 'ERR_EDB';}
  public function getKod(){return ERR_EDB;}
  public function getMsg(){
    return 'Zapytanie -> '.$this->query.' Spowodowało błąd o komunikacie : '.$this->msg.' i kodzie : '.$this->err;
    //return 'Zapytanie -> '.$this->query.' | spowodowało -> '.parent::getMessage();
  }
  public function getTrasa(){
    return parent::getTrace();
    //return parent::getTraceAsString();
  }
  public function getQuery(){return $this->query;}
  public function getErr(){return $this->err;}
  public function getArray(){
    return array(
        'err'=>$this->getErr(),
        'msg'=>$this->getMsg(),
        'query'=>$this->getquery(),
        'plik'=>$this->getFile(),
        'wiersz'=>$this->getLine(),
        'scieżka'=>$this->getTrace()
        );
  }
  protected function insertDB(){
    $query=
      "INSERT INTO throw
        (klasa,funkcja,wiersz,kod,code,nazwa,msg,db_err,query)
        VALUE(
          '".$this->getKlasa()."',
          '".$this->getFunkcja()."',
          '".$this->getLine()."',
          '".$this->getKod()."',
          '".$this->getCode()."',
          '".$this->getNazwa()."',
          '".$this->getMsg()."',
          '".$this->getErr()."',
          '".$this->getQuery()."'
        )
      ";
    @mysql_query($query);
  }
}
/**
 * Nie połączono z bazą danych
 */
class EDBConnect extends EDB{
  public function __construct($klasa, $funkcja,$insert = THROW_SAVE) {
    parent::__construct($klasa, $funkcja, '', 'Nie połączono z bazą danych', ERR_ECONNECT,'',$insert);
  }
  public function getNazwa() {
    return 'ERR_ECONNECT';
  }
  public function getKod() {
    return ERR_ECONNECT;
  }
}
/**
 * Nie istnieje rekord o podanym identyfikatorze
 */
class EDBNoRec extends EDB{
  private $id = 0;
  public function __construct($klasa, $funkcja, $query, $id, $insert = THROW_NOSAVE) {
    parent::__construct($klasa, $funkcja, $query, 'Nie istnieje rekord o identyfikatorze = '.$id, ERR_ENOREC, '', $insert);
    $this->$id = $id;
  }
  public function getId(){
    return $this->id;
  }
}
/**
 * Gdy dane wstawine do bazy lub aktualizujące dane sa nie komletne.
 * Generowany podczas tworzenia zapytania modyfukującego bazę danych lub podczas wykonywania zapytania.
 */
class EDBIncompletely extends EDB{
  /**
   *
   * @param string $klasa - Nazwa klasy w której zgłoszono wyjątek
   * @param string $funkcja - funkcja w której zgłoszono wyjątek
   * @param string $query - zapytanie które wygnenerowało wyjątek
   * @param string $msg - myquery_error();
   * @param int $err - mysql_errno()
   * @param string $user_msg - wiadmość do wyświetlenia użytkowanikowi systemu
   * @param boolean $insert - Czy informacjia o wyjątku ma być zapisana do bazy danych
   */
  public function __construct($klasa,$funkcja,$msg,$query,$err=-1,$user_msg='',$insert=THROW_NOSAVE){
    parent::__construct($klasa,$funkcja,$query,$msg,$err,$user_msg,$insert);
  }
  public function getNazwa(){return 'ERR_EDBINCOMPLETELY';}
  public function getKod(){return ERR_EDB_INCOMPLETELY;}
}
/**
 * Gdy operacja wstawiająca rekord(y) do bazy danch zakończyła się błędem.
 * Generowany podczas wykonywania zapytanie INSERT
 */
class EDBInsert extends EDB{
  /**
   *
   * @param string $klasa - Nazwa klasy w której zgłoszono wyjątek
   * @param string $funkcja - funkcja w której zgłoszono wyjątek
   * @param string $query - zapytanie które wygnenerowało wyjątek
   * @param string $msg - myquery_error();
   * @param int $err - mysql_errno()
   * @param string $user_msg - wiadmość do wyświetlenia użytkowanikowi systemu
   * @param boolean $insert - Czy informacjia o wyjątku ma być zapisana do bazy danych
   */
  public function __construct($klasa,$funkcja,$query,$msg,$err=-1,$user_msg='',$insert=THROW_NOSAVE){
    parent::__construct($klasa,$funkcja,$query,$msg,$err,$user_msg,$insert);
  }
  public function getNazwa(){return 'ERR_EDB_INSERT';}
  public function getKod(){return ERR_EDB_INSERT;}
}
/**
 * Operacja nie powiodła się z powodu braku uprawnień
 */
class EDBInsertRight extends EDBInsert{
  public function __construct($klasa, $funkcja, $query, $msg, $err = -1, $user_msg = '', $insert = THROW_NOSAVE) {
    parent::__construct($klasa, $funkcja, $query, $msg, $err, $user_msg, $insert);
  }
}
/**
 * Gdy operacja aktualizująca baze danych (UPDATE) zakończyła sie błędem
 */
class EDBUpdate extends EDB{
  /**
   * @param string $klasa - Nazwa klasy w której zgłoszono wyjątek
   * @param string $funkcja - funkcja w której zgłoszono wyjątek
   * @param string $query - zapytanie które wygnenerowało wyjątek
   * @param string $msg - myquery_error();
   * @param int $err - mysql_errno()
   * @param string $user_msg - wiadmość do wyświetlenia urzytkowanikowi systremu
   * @param boolean $insert - Czy informacjia o wyjątku ma być zapisana do azy dancyh
   */
  public function __construct($klasa,$funkcja,$query,$msg,$err=-1,$user_msg='',$insert=THROW_NOSAVE){
    parent::__construct($klasa,$funkcja,$query,$msg,$err,$user_msg,$insert);
  }
  public function getNazwa(){return 'ERR_EDB_UPDATE';}
  public function getKod(){return ERR_EDB_UPDATE;}
}
/**
 * Operacja nie powiodła się z powodu braku uprawnień
 */
class EDBUpdateRight extends EDBUpdate{
  public function __construct($klasa, $funkcja, $query, $msg, $err = -1, $user_msg = '', $insert = THROW_NOSAVE) {
    parent::__construct($klasa, $funkcja, $query, $msg, $err, $user_msg, $insert);
  }
}
/**
 * Po próbie zaktualizowania rekordu gdy między odczytem tego rekordu a próbą aktualizacji jego aktualizacji został zaktualizowany przez innego "klienta".
 */
class EDBUpdateUpdated extends EDBUpdate{
  public function __construct($klasa, $funkcja, $query, $msg, $err = -1, $user_msg = '', $insert = THROW_NOSAVE) {
    parent::__construct($klasa, $funkcja, $query, $msg, $err, $user_msg, $insert);
  }
}
/**
 * Gdy zapytanie SELECT zwróci błąd
 */
class EDBSelect extends EDB{
  /**
   * @param string $klasa - Nazwa klasy w której zgłoszono wyjątek
   * @param string $funkcja - funkcja w której zgłoszono wyjątek
   * @param string $query - zapytanie które wygnenerowało wyjątek
   * @param string $msg - myquery_error();
   * @param int $err - mysql_errno()
   * @param string $user_msg - wiadmość do wyświetlenia użytkowanikowi systemu
   * @param boolean $insert - Czy informacjia o wyjątku ma być zapisana do bazy danych
   */
  public function __construct($klasa,$funkcja,$query,$msg,$err=-1,$user_msg='',$insert=THROW_NOSAVE){
    parent::__construct($klasa,$funkcja,$query,$msg,$err,$user_msg,$insert);
  }
  public function getNazwa() {return 'ERR_EDB_SELECT';}
  public function getKod(){return ERR_EDB_SELECT;}
}
/**
 * Operacja nie powiodła się z powodu braku uprawnień
 */
class EDBSelectRight extends EDBSelect{
  public function __construct($klasa, $funkcja, $query, $msg, $err = -1, $user_msg = '', $insert = THROW_NOSAVE) {
    parent::__construct($klasa, $funkcja, $query, $msg, $err, $user_msg, $insert);
  }
}
/**
 * Gdy próba skasowania rekordu zwróci jakiś błąd.
 * Bład nie związany z zależnościami między kluczami. Kasowanie rekordu może odbywać się na podstawie
 * rozbudowanej klauzuli WHERE wtedy nie można określić jaka kolumna przy jakiej
 * wartości spowodowała ten błąd więc w takim wypadku zmienne $kolumna i $wartość
 * ustawiane są na ''.
 */
class EDBDelete extends EDB{
  var $tabela;
  var $kolumna;
  var $wartosc;
  var $querystr;
  /**
   *
   * @param string $klasa - Nazwa klasy w której zgłoszono wyjątek
   * @param string $funkcja - funkcja w której zgłoszono wyjątek
   * @param string $tabela - tabela w której próbowano usunąć wiersz
   * @param string $kolumna - kolumna na podstawie której próbowano usunąć wiersz
   * @param string $wartosc - wartość na podstawie której próbowano usunąć wiersz
   * @param string $query - zapytanie które wygnenerowało wyjątek
   * @param string $msg - myquery_error();
   * @param int $err - mysql_errno()
   * @param string $user_msg - wiadmość do wyświetlenia użytkowanikowi systemu
   * @param boolean $insert - Czy informacjia o wyjątku ma być zapisana do bazy danych
   */
  public function __construct($klasa,$funkcja,$tabela,$kolumna,$wartosc,$query,$msg,$err=-1,$user_msg='',$insert=THROW_NOSAVE){
    parent::__construct($klasa,$funkcja, $query,$msg,$err,$user_msg,$insert);
    $this->tabela=$tabela;
    $this->kolumna=$kolumna;
    $this->wartosc=$wartosc;
  }
  public function getNazwa(){return 'ERR_EDB_DELETE';}
  public function getKod(){return ERR_EDB_DELETE;}
  public function getTabela(){return $this->tabela;}
  public function getKolumna(){return $this->kolumna;}
  public function getWartosc(){return $this->wartosc;}
  public function getquerystr(){return $this->querystr;}
  public function getArray(){
    return array(
        'msg'=>$this->getMsg(),
        'tabela'=>$this->getTabela(),
        'kolumna'=>$this->getKolumna(),
        'wartosc'=>$this->getWartosc(),
        'query'=>$this->getqueryStr(),
        'plik'=>$this->getFile(),
        'wiersz'=>$this->getLine()
        );
  }
}
/**
 * Operacja nie powiodła się z powodu braku uprawnień
 */
class EDBDeleteRight extends EDBDelete{
  public function __construct($klasa, $funkcja, $tabela, $kolumna, $wartosc, $query, $msg, $err = -1, $user_msg = '', $insert = THROW_NOSAVE) {
    parent::__construct($klasa, $funkcja, $tabela, $kolumna, $wartosc, $query, $msg, $err, $user_msg, $insert);
  }
}
//
///**
//  * Gdy podjęto próbę kasowania rekordu powiazanego kluczami obcymi
//  */
//class EDBForeignKey extends EDB{
//  var $tabela;
//  var $rekord_id;
//  /**
//   *
//   * @param string $klasa - Nazwa klasy w której zgłoszono wyjątek
//   * @param string $funkcja - funkcja w której zgłoszono wyjątek
//   * @param string $tabela - tabela w której próbowano usunąć wiersz
//   * @param uint $rekord_id zawiera klucz główny rekordu na którym wykonywano błędną operacje
//   * @param string $query - zapytanie które wygnenerowało wyjątek
//   * @param string $msg - myquery_error();
//   * @param int $err - mysql_errno()
//   * @param string $user_msg - wiadmość do wyświetlenia użytkowanikowi systemu
//   * @param boolean $insert - Czy informacjia o wyjątku ma być zapisana do bazy danych
//   */
//  public function __construct($klasa,$funkcja,$tabela,$rekord_id=null,$query='',$msg='',$err=-1,$user_msg='',$insert=THROW_NOSAVE){
//    parent::__construct($klasa,$funkcja, $query,$msg,$err,$user_msg,$insert);
//    $this->tabela=$tabela;
//    $this->rekord_id=$rekord_id;
//  }
//  public function getNazwa(){return 'ERR_EDB_FOREIGNKEY';}
//  public function getKod(){return ERR_EDB_FOREIGNKEY;}
//
//	public function getTabela(){return $this->tabela;}
//  /**
//   * @return uint
//   * @deprecated
//   * @uses EDBForeignKey::getRekordId()
//   */
//  public function getRekord(){return $this->rekord_id;}
//  public function getRekordId(){return $this->rekord_id;}
//  public function getArray(){
//    return array(
//        'msg'=>$this->getMsg(),
//        'tabela'=>$this->getTabela(),
//        'rekord'=>$this->getRekord(),
//        'error'=>$this->getErr(),
//        'plik'=>$this->getFile(),
//        'wiersz'=>$this->getLine()
//        );
//  }
//}
///**
// * Gdy wymagane jest by w bazie danych jakieś pole było unikalne a nowo podana
// * wartość zaburza unikalność rzucany jest ten wyjątek
// */
//class EDBNotUnique extends EDB{
//  var $tabela;
//  var $kolumna;
//  var $wartosc;
//  /**
//   *
//   * @param string $klasa - Nazwa klasy w której zgłoszono wyjątek
//   * @param string $funkcja - funkcja w której zgłoszono wyjątek
//   * @param string $tabela - tabela w której próbowano usunąć wiersz
//   * @param string $kolumna - kolumna w której zaburzono unikalność danych
//   * @param string $wartosc - wartość która zaburzałą unikalność danych
//   * @param string $query - zapytanie które wygnenerowało wyjątek
//   * @param string $msg - myquery_error();
//   * @param int $err - mysql_errno()
//   * @param string $user_msg - wiadmość do wyświetlenia użytkowanikowi systemu
//   * @param boolean $insert - Czy informacjia o wyjątku ma być zapisana do bazy danych
//   */
//  public function __construct($klasa,$funkcja,$tabela,$kolumna,$wartosc,$query='',$msg='',$err=-1,$user_msg='',$insert=THROW_NOSAVE){
//    parent::__construct($klasa,$funkcja, $query,$msg,$err,$user_msg,$insert);
//    $this->tabela=$tabela;
//    $this->kolumna=$kolumna;
//    $this->wartosc=$wartosc;
//  }
//  public function getNazwa(){return 'ERR_EDB_NOTUNIQUE';}
//  public function getKod(){return ERR_EDB_NOTUNIQUE;}
//  public function getTabela(){return $this->tabela;}
//  public function getKolumna(){return $this->kolumna;}
//  public function getWartosc(){return $this->wartosc;}
//  public function getArray(){
//    return array(
//        'msg'=>$this->getMsg(),
//        'tabela'=>$this->getTabela(),
//        'kolumna'=>$this->getKolumna(),
//        'wartosc'=>$this->getWartosc(),
//        'plik'=>$this->getFile(),
//        'wiersz'=>$this->getLine()
//        );
//  }
//}
/**
 * Operacja nie wykonała operacji w sytucjach innych niz ochrona relacji.
 *  - SELECT nie zwrócił żadnego rekordu
 *  - INSERT nie wstawił żadnego rekordu
 *  - UPDATE nie zaktualizował żadnego rekordu
 *  - DELETE nie usunęło żadnego rekordu
 */
class EDBOperationNull extends EDB{
  public function __construct($klasa, $funkcja, $query) {
    parent::__construct($klasa, $funkcja, $query, '');
  }
}
?>
