<?php
namespace pjpl;
class Firewall {

	/**
	 * @param boolean $bool
	 * @return boolean
	 */
	public function boolean($bool){
		return (boolean)$bool;
	}

	/**
   * Date : xxxx-xx-xx
   * @param string $date
   * @return string
   */
  public function date($date){
    return trim($date);
  }

  /**
   * E-mail
   * @param string $email
   * @return string
   */
  public function email($email){
    return trim($email);
  }
  /**
   * @param string $int
   * @return int
   */
  public function int($int){
    return (int)$int;
  }
  /**
   * @param string $login
   * @return string
   */
  public function login($login){
    return $login;
  }

  /**
   * Tylko liczby ale bez konwersji na liczbę - pozostaje jako napis
   * @param string $number
   * @return string
   */
  public function number($number){
    return trim($number);
  }
  public function password($password){
    return $password;
  }

  /**
   * Liczby literu i "-"
   * @param string $serial_number
   * @return string
   */
  public function serialNumber($serial_number){
    return trim($serial_number);
  }

  /**
   * Dowolny string
   * @param string $string
   * @return string
   */
  public function string($string){
    if(empty($string)){
      return '';
    }
//    return filter_var( trim($string), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    return trim($string);
  }
  /**
   * Numer telefonu
   * @param string $telefon_number
   * @return string
   */
  public function telefonNumber($telefon_number){
    return trim($telefon_number);
  }

  /**
   * Musi być jednym ciągem znaków złożonym z liczb i liter oraz _ nie podzielony znakami białymi
   * @param string $token
   * @return string
   */
  public function token($token){
    return trim($token);
  }
  /**
   * Musi być jednym wyrazem złożonym samych liter.
   * @param string $word
   * @return string
   */
  public function word($word){
    return trim($word);
  }

  static public  function getInstance(){
    if(self::$instance === null){
      self::$instance = new Firewall();
    }
    return self::$instance;
  }
  static private $instance = null;
}

?>
