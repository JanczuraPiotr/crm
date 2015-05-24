<?php
/**
 * @package crmsw
 * @subpackage ajax
 * @author Piotr Janczura <piotr@janczura.pl>
 */
require_once '../../config.php';
if( !isset($_SESSION['USER_STATUS'])){
  echo json_encode(array('success'=>FALSE,'code'=>ERR_NOT_LOGIN,'msg'=>'ERR_NOT_LOGIN'));
  exit ;
}

if(isset($_REQUEST['action'])){
	$action = $_REQUEST['action'];
}else{
	$action = null;
}

//print_r($_REQUEST);
//print_r($HTTP_RAW_POST_DATA);

switch ($action) {
	case 'read':
		$ZatrudnianieRead = new L_ZatrudnianieRead();
		$ZatrudnianieRead->fromRequest($_REQUEST);
		$ZatrudnianieRead->action();
		echo $ZatrudnianieRead->getJson();
		break;
	case 'zatrudnij':
		$ZatrudnianieZatrudnij = new L_ZatrudnianieZatrudnij();
		$ZatrudnianieZatrudnij->fromRequest($HTTP_RAW_POST_DATA);
		$ZatrudnianieZatrudnij->action();
		echo $ZatrudnianieZatrudnij->getJson();
		break;
	case 'zwolnij':
		$ZatrudnianieDelete = new L_ZatrudnianieZwolnij();
		$ZatrudnianieDelete->fromRequest($_REQUEST);
		$ZatrudnianieDelete->action();
		echo $ZatrudnianieDelete->getJson();
		break;

	default:
		break;
}