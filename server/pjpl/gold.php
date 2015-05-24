<?php
namespace pjpl\depreciate;

/**
 * @package pjpl
 * @subpackage lib
 * @author <piotr@janczura.pl>
 */




//------------------------------------------------------------------------------
// 2012-1-06
//==============================================================================
function zapiszLog($kto,$log){
  mysql_query("INSERT INTO log('kto','log') VALUES('".mysql_real_escape_string($kto)."','".mysql_real_escape_string($log)."')");
}
//==============================================================================
function zapiszErr($kto,$err){
  mysql_query("INSERT INTO err('kto','err') VALUES('".mysql_real_escape_string($kto)."','".mysql_real_escape_string($err)."')");
}
//==============================================================================
/**
 * Oblicza kwotę z uwzględnieniem rabatu
 * @param decimal $kwota
 * @param decimal $rabat
 * @return decimal
 */
function zRabatem($kwota,$rabat){
  return round($kwota-($kwota * $rabat/100),2);
}
//==============================================================================
function odsetki($kwota,$procent,$dni){
  /**
   * o = ((k * (p/100)) / 365) * d
   * o - odsetki
   * k - kwota
   * d - liczba dni zwłoki
   * p - oprocentowanie
   */
  return (($kwota * ( $procent/100)) / 365) * $dni ;
}
//==============================================================================
/**
 * Zwraca różnicę dat w jednostce podanej w wywołaniu jako parametr : $jednostka_czasu.
 * @param data $data_poczatek - data początku okresu
 * @param data $data_koniec - data końca okresu
 * @param char $jednostka_czasu - m-minut , g-godzina , d-dzien, s-sekunda
 * @return int - różnica data w jednostkach podanych w wywołaniu funkcji
 */
function roznicaDat($data_poczatek,$data_koniec,$jednostka_czasu='d'){
  $tablica=array('m'=>60,'g'=>3600,'d'=>86400,'s'=>1);
  return round(((strtotime($data_koniec) - strtotime($data_poczatek)) / $tablica[$jednostka_czasu]));
}
//=============================================================================
/**
 * Zwraca date w formacie RRRRMMDD np : 20101122
 * @deprecated
 */
function DateNowCond(){
  $sql="SELECT DATE_FORMAT(CURDATE(),\'%Y%m%d\')";
  $res=mysql_fetch_array(mysql_query($sql));
  return $res[0];
}
//=============================================================================
/**
 * Zwraca stempel czasu w formacie RRRRMMDDHHMMSS
 * @deprecated
 */
function TimeStampCond(){
  $sql="SELECT DATE_FORMAT(CURRENT_TIMESTAMP(),'%Y%m%d%H%i%S')";
  $res=mysql_fetch_array(mysql_query($sql));
  return $res[0];
}
//=============================================================================
/**
 * Do daty : $date dodaje ilość miesięcy : $mies
 * @deprecated
 */
function DateAddMies01($date,$mies){
  $tmp=mysql_fetch_array(mysql_query("SELECT DATE_ADD('".$date."',INTERVAL ".$mies." MONTH)"));
  $tmp[0][8]='0';
  $tmp[0][9]='1';
  return $tmp[0];
}
//=============================================================================
/**
 * Konwertuje licznę tak żeby wyglądała jak cena
 * przykład :  <br>
 * dla wartości 1.22 zrobi 1,22 zł <br>
 * dla wartości 100 zrobi 100,00 zł <br>
 * @var int $kwota - jakaś liczba
 * @return string "liczba" która wygląda jak cena
 */
function zl($kwota){
  if(empty($kwota)){
    return '0,00 zł';
  }
  return number_format($kwota,2,',',' ').' zł';
}
//=============================================================================
/**
 * Słowny zapis liczby
 * @author nieznany
 * @author Piotr Janczura <piotr@janczura.pl> - drobne poprawki
 * @var int jakaś liczba
 * @return słowny zapis tej liczby
 */
function slownie ($kw=0) {
  // Dodałem poniższy warunek ponieważ oryginalna funkcja nie radziła sobie z wartościami ujemnymi.
  // Na końcu funkcji napis 'znak' jest dodawy na początek zwaracanego napisu
  // Piotr Janczura.
  if($kw<0){
    $znak='minus ';
    $kw*=-1;
  }else{
    $znak='';
  }
  $kw=number_format($kw,2,',','');
  $t_a = array('','sto','dwieście','trzysta','czterysta','pięćset','sześćset','siedemset','osiemset','dziewięćset');
  $t_b = array('','dziesięć','dwadzieścia','trzydzieści','czterdzieści','pięćdziesiąt','sześćdziesiąt','siedemdziesiąt','osiemdziesiąt','dziewięćdziesiąt');
  $t_c = array('','jeden','dwa','trzy','cztery','pięć','sześć','siedem','osiem','dziewięć');
  $t_d = array('dziesięć','jedenaście','dwanaście','trzynaście','czternaście','piętnaście','szesnaście','siednaście','osiemnaście','dziewiętnaście');

  $t_kw_15 = array('septyliard','septyliardów','septyliardy');
  $t_kw_14 = array('septylion','septylionów','septyliony');
  $t_kw_13 = array('sekstyliard','sekstyliardów','sekstyliardy');
  $t_kw_12 = array('sekstylion','sekstylionów','sepstyliony');
  $t_kw_11 = array('kwintyliard','kwintyliardów','kwintyliardy');
  $t_kw_10 = array('kwintylion','kwintylionów','kwintyliony');
  $t_kw_9 = array('kwadryliard','kwadryliardów','kwaryliardy');
  $t_kw_8 = array('kwadrylion','kwadrylionów','kwadryliony');
  $t_kw_7 = array('tryliard','tryliardów','tryliardy');
  $t_kw_6 = array('trylion','trylionów','tryliony');
  $t_kw_5 = array('biliard','biliardów','biliardy');
  $t_kw_4 = array('bilion','bilionów','bilony');
  $t_kw_3 = array('miliard','miliardów','miliardy');
  $t_kw_2 = array('milion','milionów','miliony');
  $t_kw_1 = array('tysiąc','tysięcy','tysiące');
  $t_kw_0 = array('złoty','złotych','złote');
  $kw_w='';// Inicjacja dodana przez Piotr Janczura
  if ($kw!='') {
    $kw=(substr_count($kw,',')==0) ? $kw.',00':$kw;
    $tmp=explode(",",$kw);
    $ln=strlen($tmp[0]);
    $tmp_a=($ln%3==0) ? (floor($ln/3)*3):((floor($ln/3)+1)*3);
    $l_pad='';
    for($i = $ln; $i < $tmp_a; $i++) {
      $l_pad .= '0';
      $kw_w = $l_pad . $tmp[0];
    }
    $kw_w=($kw_w=='') ? $tmp[0]:$kw_w;
    $paczki=(strlen($kw_w)/3)-1;
    $p_tmp=$paczki;
    $kw_slow='';
    for($i=0;$i<=$paczki;$i++) {
      $t_tmp='t_kw_'.$p_tmp;
      $p_tmp--;
      $p_kw=substr($kw_w,($i*3),3);
      $kw_w_s=($p_kw{1}!=1) ? $t_a[$p_kw{0}].' '.$t_b[$p_kw{1}].' '.$t_c[$p_kw{2}]:$t_a[$p_kw{0}].' '.$t_d[$p_kw{2}];
      if(($p_kw{0}==0) && ($p_kw{2}==1)&&($p_kw{1}<1)){
        $ka=${$t_tmp}[0]; //możliwe że $p_kw{1}!=1
      }else if( ( $p_kw{2} > 1 && $p_kw{2} < 5 ) && $p_kw{1} != 1 ){
        $ka=${$t_tmp}[2];
      }else{
        $ka=${$t_tmp}[1];
      }
      $kw_slow.=$kw_w_s.' '.$ka.' ';
    }
  }
  $text = $znak.$kw_slow.' '.number_format($tmp[1]).'/100 gr.';
  return $text;
}
//-----------------------------------------------------------------------------
// TESTY
//==============================================================================
function sprawdzDate($data){
  return preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/D',$data);
}
//=============================================================================
/**
 * Sprawdza czy parametr jest poprawnie zapisanym adresem email
 * @param string $mail
 * @return boolean
 */
function sprawdzEmail($mail){
  return preg_match('/^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]{1,})*\.([a-zA-Z]{2,}){1}$/D',$mail);
}
//=============================================================================
/**
 * Sprawdza czy parametr jest poprawnie zapisanym kodem pocztowym
 * @param string $kod_poczt
 * @return boolean
 */
function sprawdzKodPoczt($kod_poczt){
  if( preg_match('/^[0-9]{2}-[0-9]{3}$/D',$kod_poczt)==true){
    return true;
  }else{
    return false;
  }
}
//=============================================================================
/**
 * Czy NIP
 * Sprawdza czy parametr jest poprawnie zapisanym numerem NIP
 * Jeżeli $nipwe jest poprawnie zapisanym nipem zwraca true
 * @param string $nipwe
 * @return boolean
 */
function sprawdzNip($nipwe){
  $table=Array(6,5,7,2,3,4,5,6,7); //tablica z wagami
  $NIP=array();
  for ($licz=0; $licz<strlen($nipwe); $licz++){
    if (is_numeric($nipwe[$licz])){
      $NIP[]=$nipwe[$licz];  //petla tworzaca z nipu tablice liczb
    }else if($nipwe[$licz]!='-'){
      return false;
    }
  }
  if (count($NIP)==10)  //jesli jest 10 liczb
  {
    $suma=0;
    for ($licz=0; $licz<9; $licz++){
      $wynik[]=$NIP[$licz]*$table[$licz];  //pomnoz kazda przez odpowiadajaca jej wage
    }
    for ($licz=0; $licz<9; $licz++){
      $suma+=$wynik[$licz]; //zsumuj
    }
    $suma=$suma%11; //podziel modulo przez 11
    if ($suma==$NIP[9]){
      return true;   //w zaleznosci od wynik zwroc odpowiednia wartosc
    }else{
      return false;
    }
  }
  return false;
}
//=============================================================================
/**
 * Czy parametr jest słowem
 * Sprzawdza czy podany napis jest złożony tylko z jednego słowa zapisanego
 * bez urzycia innych znaków niż a-zA-Z
 * @param string word - jeden wyraz
 * @return boolean
 */
function sprawdz1Word($word){
  return preg_match('/^[a-źA-Ź]+$/D',$word);
}
//=============================================================================
/**
 * Czy parametr to Liczba całkowita
 * Jeżeli liczba całkowita zwraca true
 * @param string liczba która powinna być słownym zapisem liczby całkowitej
 * @return boolean
 */
function sprawdzInt($int){
  return preg_match('/^[\-\+]?[\s]?[0-9]+$/D',$int);
}
//=============================================================================
/**
 * Czy parametr to Liczba z ułamkiem
 * Jeżeli liczba z ułamkiem zwraca true
 * @param string $double string który powinien być zapisem słownym liczby z ułamkiem
 * @return boolean
 */
function sprawdzDouble($double){
  return preg_match('/^[\-\+]?[\s]?[0-9]+\.[0-9]+$/D',$double);
}
//=============================================================================
/**
 * Zwraca RRRR-MM-01
 * Zwraca datę pierwszego dnia miesiąca który będzie za $przesyniecie_w_mies od teraz
 * @param int $przesyniecie_w_mies - ma zawierać liczbę będącą przesunięciem od teraz w
 * miesiącach od miesiąca w którym sprawdzany jest okres
 * @return string data;
 */
function OkresOd($przesuniecie_w_mies){
 $data=mysql_fetch_array(mysql_query("SELECT LAST_DAY(DATE_ADD(CURDATE(), INTERVAL $przesuniecie_w_mies MONTH))"));
  $data=$data[0];
  $data[8]="0";
  $data[9]="1";
  return $data;
}
//=============================================================================
/**
 * Zwraca RRRR-MM-31[30][29][28] w zależności od miesiąca
 * Zwraca datę ostatniego dnie miesiąca który będzie za $przesunięcie_w_mies od teraz
 * @param int $przesuniecie_w_mies ma zawierać liczbę będącą przesunięciem w miesiącach
 * od miesiąca w którym sprawdzany jest okres
 * @return string data
 */
function OkresDo($przesuniecie_w_mies){
 $data=mysql_fetch_array(mysql_query("SELECT LAST_DAY(DATE_ADD(CURDATE(), INTERVAL $przesuniecie_w_mies MONTH))"));
  return $data[0];
}
//============================================================================
/**
 * Usówa ogonki
 * Zdarza się choć nie wiadomo czemu że czasami trzeba z tekstu pousuwac polskie znaki.
 * @author tosiek - http://tosiek.pl/
 * @param string $text - tekst z ogonkami
 * @return string tekst bez ogonków
 */
function remove_pl($text) {
$from = array(
        "\xc4\x85", "\xc4\x87", "\xc4\x99",
        "\xc5\x82", "\xc5\x84", "\xc3\xb3",
        "\xc5\x9b", "\xc5\xba", "\xc5\xbc",
        "\xc4\x84", "\xc4\x86", "\xc4\x98",
        "\xc5\x81", "\xc5\x83", "\xc3\x93",
        "\xc5\x9a", "\xc5\xb9", "\xc5\xbb",
);
$clear = array(
        "\x61", "\x63", "\x65",
        "\x6c", "\x6e", "\x6f",
        "\x73", "\x7a", "\x7a",
        "\x41", "\x43", "\x45",
        "\x4c", "\x4e", "\x4f",
        "\x53", "\x5a", "\x5a",
);
if(is_array($text)) {
        foreach($text as $key => $value) {
                $array[str_replace($from, $clear, $key)]= str_replace($from, $clear, $value);
        }
        return $array;
}else {
        return str_replace($from, $clear, $text);
}
}
?>
