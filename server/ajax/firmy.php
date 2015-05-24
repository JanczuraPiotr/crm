<?php
/**
 * @package crmsw
 * @subpackage ajax
 * @author Piotr Janczura <piotr@janczura.pl>
 */
//@session();
require_once '../../config.php';
if( !isset($_SESSION['USER_STATUS'])){
	@session_destroy();
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
		$FirmyCreate = new L_FirmyCreate();
		$FirmyCreate->fromRequest($HTTP_RAW_POST_DATA);
		$FirmyCreate->action();
		echo $FirmyCreate->getJson();
		break;
	case 'read':
		$FirmyRead = new L_FirmyRead();
		$FirmyRead->fromRequest($_REQUEST);
		$FirmyRead->action();
		echo $FirmyRead->getJson();
		break;
	case 'update':
		$FirmyUpdate = new L_FirmyUpdate();
		$FirmyUpdate->fromRequest($HTTP_RAW_POST_DATA);
		$FirmyUpdate->action();
		echo $FirmyUpdate->getJson();
		break;
	case 'delete':
		$FirmyDelete = new L_FirmyDelete();
		$FirmyDelete->fromRequest($HTTP_RAW_POST_DATA);
		$FirmyDelete->action();
		echo $FirmyDelete->getJson();
		break;

	default:
		break;
}