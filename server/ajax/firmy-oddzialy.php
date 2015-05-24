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
		$FirmyOddzialyCreate = new L_FirmyOddzialyCreate();
		$FirmyOddzialyCreate->fromRequest($HTTP_RAW_POST_DATA);
		$FirmyOddzialyCreate->action();
		echo $FirmyOddzialyCreate->getJson();
		break;
	case 'read':
		$FirmyOddzialyRead = new L_FirmyOddzialyRead();
		$FirmyOddzialyRead->fromRequest($_REQUEST);
		$FirmyOddzialyRead->action();
		echo $FirmyOddzialyRead->getJson();
		break;
	case 'update':
		$FirmyOddzialyUpdate = new L_FirmyOddzialyUpdate();
		$FirmyOddzialyUpdate->fromRequest($HTTP_RAW_POST_DATA);
		$FirmyOddzialyUpdate->action();
		echo $FirmyOddzialyUpdate->getJson();
		break;
	case 'delete':
		$FirmyOddzialyDelete = new L_FirmyOddzialyDelete();
		$FirmyOddzialyDelete->fromRequest($HTTP_RAW_POST_DATA);
		$FirmyOddzialyDelete->action();
		echo $FirmyOddzialyDelete->getJson();
		break;

	default:
		break;
}