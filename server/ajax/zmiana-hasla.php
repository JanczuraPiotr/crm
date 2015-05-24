<?php
/**
 * @package crmsw
 * @subpackage ajax
 * @author Piotr Janczura <piotr@janczura.pl>
 */
require_once '../../config.php';

if( !isset($_SESSION['USER_TYP'])){
  echo json_encode(array('success'=>false,'message'=>'Wylogowano','data'=>  array()));
	exit;
}
//print_r($_REQUEST);
//print_r($HTTP_RAW_POST_DATA);
$ZmianaHasla = new L_ZmianaHasla();
$ZmianaHasla->fromRequest($_REQUEST);
$ZmianaHasla->action();
echo $ZmianaHasla->getJson();