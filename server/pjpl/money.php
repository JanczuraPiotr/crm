<?
/**
 * @package pl.janczura.piotr
 * @subpackage lib.php
 * @author <piotr@janczura.pl>
 */
//------------------------------------------------------------------------------
// 2013-10-11
//------------------------------------------------------------------------------

require_once 'error.php';


class EMoneyFormat extends ENumberFormat{
  /**
   * @param string $klasa - Nazwa klasy w której zgłoszono wyjątek
   * @param string $funkcja - funkcja w której zgłoszono wyjątek
   * @param string $name - nazwa zmienej
   * @param string $value - wartość zmiennej
   * @param string $message
   * @param string $user_msg
   * @param string $throw_save
   */
  public function __construct($klasa, $funkcja, $name, $value, $message = 'Wartość nie jest liczbą typu finansowego', $user_msg = 'Podana wartość nie jest liczbą typu finansowego', $throw_save = THROW_NOSAVE) {
    parent::__construct($klasa, $funkcja, $name, $value, $message, $user_msg, $throw_save);
  }
}

class Money{
  public function getValue(){
    return $this->value;
  }
  public function setValue($value){
    $this->value = $value;
  }
  //-----------------------------------------------------------------------------
  // params
  /**
   * @var decimal[20,2]
   */
  private $value = 0.00;
}
/**
 * Konwertuje wejściowy na
 * @param string $value
 * @return double (float)
 * @throws EDoubleFormat
 */
function toDouble($value){
  if( preg_match('/^[\-\+]?[\s]?[0-9]+\.[0-9]+$/D',$value) ){
    return (double)$value;
  }else{
    $value = str_replace(',', '.', $value);
    if(preg_match('/^[\-\+]?[\s]?[0-9]+\.[0-9]+$/D',$value)){
      return (double)$value;
    }
  }
  $value.='.00';
  if(preg_match('/^[\-\+]?[\s]?[0-9]+\.[0-9]+$/D',$value)){
    return round( $value, 2);
  }
  $E = new EDoubleFormat(__CLASS__, __FUNCTION__, 'value', $value);
  throw $E;
}
/**
 * Jeżeli $value da się konwertować do double zwraca true
 * @param string $value
 * @return decimal
 * @throws EDecimalFormat
 */
function toDecimal($value){
  if( preg_match('/^[\-\+]?[\s]?[0-9]+\.[0-9]+$/D',$value) ){
    return round( $value, 2);
  }else{
    $value = str_replace(',', '.', $value);
    if(preg_match('/^[\-\+]?[\s]?[0-9]+\.[0-9]+$/D',$value)){
      return round( $value, 2);
    }
  }
  $value.='.00';
  if(preg_match('/^[\-\+]?[\s]?[0-9]+\.[0-9]+$/D',$value)){
    return round( $value, 2);
  }
  throw new EDecimalFormat(__CLASS__, __FUNCTION__, 'value', $value);
}
/**
 * Konwertuje liczbę tak by można było ją do stringu z dwoma miejscami po przecinku
 * @param decimal $kwota
 * @return string
 * @throws EDecimalFormat
 */
function toMoney($value){
  return number_format(toDecimal($value),2,',',' ');
}
/**
 * Konwertuje licznę tak żeby wyglądała jak cena
 * przykład :  <br>
 * dla wartości 1.22 zrobi 1,22 zł <br>
 * dla wartości 100 zrobi 100,00 zł <br>
 * @var int $value - jakaś liczba
 * @return string "liczba" która wygląda jak cena
 */
function toPLN($value){
  if(empty($value)){
  }
  return toMoney($value).' zł';
}
?>
