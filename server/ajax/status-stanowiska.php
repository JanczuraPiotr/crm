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
	case 'create':
		$StatusStanowiskaCreate = new L_StatusStanowiskaCreate();
		$StatusStanowiskaCreate->fromRequest($HTTP_RAW_POST_DATA);
		$StatusStanowiskaCreate->action();
		echo $StatusStanowiskaCreate->getJson();
		break;
	case 'read':
		$StatusStanowiskaRead = new L_StatusStanowiskaRead();
		$StatusStanowiskaRead->fromRequest($_REQUEST);
		$StatusStanowiskaRead->action();
		echo $StatusStanowiskaRead->getJson();
		break;
	case 'update':
		$StatusStanowiskaUpdate = new L_StatusStanowiskaUpdate();
		$StatusStanowiskaUpdate->fromRequest($HTTP_RAW_POST_DATA);
		$StatusStanowiskaUpdate->action();
		echo $StatusStanowiskaUpdate->getJson();
		break;
	case 'delete':
		$StatusStanowiskaDelete = new L_StatusStanowiskaDelete();
		$StatusStanowiskaDelete->fromRequest($HTTP_RAW_POST_DATA);
		$StatusStanowiskaDelete->action();
		echo $StatusStanowiskaDelete->getJson();
		break;

	default:
		break;
}