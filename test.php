#!/usr/bin/php
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

//require_once 'server/lib/lib.php';
//require_once 'server/lib/time.php';
//require_once 'server/lib/money.php';
//require_once 'server/lib/error.php';

require_once 'config.php';

echo PHP_EOL.PHP_EOL.PHP_EOL;

echo '#############################################################################'.PHP_EOL;
echo '#############################################################################'.PHP_EOL;
echo 'Start testu   = '.microDate().PHP_EOL;
echo '============================================================================='.PHP_EOL.PHP_EOL;
$start = µs();
//-----------------------------------------------------------------------------

//abstract class A{
//  private $paramA;
//  public function __construct($paramA = null) {
//    $this->paramA = $paramA;
//  }
//  public function __destruct() {
//    echo __CLASS__."::".__METHOD__.PHP_EOL;
//  }
//}
//class C extends A{
//  private $paramC;
//  public function __construct($paramC = null, $paramA = null) {
//    parent::__construct($paramA);
//    return;
//    $this->paramC = $paramC;
//  }
//  public function __destruct() {
//    parent::__destruct();
//    echo __CLASS__."::".__METHOD__.PHP_EOL;
//  }
//}
//class B {
//  private $paramB;
//  private $array = array();
//  public function __construct(A $A) {
//    $this->paramB = $A;
//    $this->array[] = 1;
//    $this->array[] = 2;
//    $this->array[] = 3;
//  }
//  public function getArray(){
//    return $this->array;
//  }
//}
//
//$C = new C(1, 2);
//$B = new B($C);
//
//echo print_r($C,true).PHP_EOL;
//echo print_r($B,true).PHP_EOL;

$a1 = array(1,2,3,4,5,6);
$a2 = array(3,5,6,7,8,9);
$a3 = array_intersect($a2, $a1);

print_r($a1);
print_r($a2);
print_r($a3);



stop:
//-----------------------------------------------------------------------------
$stop = µs();
echo PHP_EOL;
echo '============================================================================='.PHP_EOL;
echo 'Stop testu = '.microDate().PHP_EOL;
echo 'Czas testu = '. ($stop - $start) / 1000000.0 .' s' . PHP_EOL.PHP_EOL;
echo '#############################################################################'.PHP_EOL.PHP_EOL;

?>
