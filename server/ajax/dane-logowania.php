<?php
/**
 * @package crmsw
 * @subpackage ajax
 * @author Piotr Janczura <piotr@janczura.pl>
 */
//@session_start();
require_once '../../config.php';

if( !isset($_SESSION['USER_STATUS'])){
  echo json_encode(array('success'=>false,'message'=>'Wylogowano','data'=>  array()));
	exit ;
}
$ZmianaHasla = new L_ZmianaHasla();
$ZmianaHasla->fromRequest($_REQUEST);
$ZmianaHasla->action();
echo $ZmianaHasla->getJson();