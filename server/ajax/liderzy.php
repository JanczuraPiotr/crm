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
		$LiderzyCreate = new L_LiderzyCreate();
		$LiderzyCreate->fromRequest($HTTP_RAW_POST_DATA);
		$LiderzyCreate->action();
		echo $LiderzyCreate->getJson();
		break;
	case 'read':
		$LiderzyRead = new L_LiderzyRead();
		$LiderzyRead->fromRequest($_REQUEST);
		$LiderzyRead->action();
		echo $LiderzyRead->getJson();
		break;
	case 'update':
		$LiderzyUpdate = new L_LiderzyUpdate();
		$LiderzyUpdate->fromRequest($HTTP_RAW_POST_DATA);
		$LiderzyUpdate->action();
		echo $LiderzyUpdate->getJson();
		break;
	case 'delete':
		$LiderzyDelete = new L_LiderzyDelete();
		$LiderzyDelete->fromRequest($HTTP_RAW_POST_DATA);
		$LiderzyDelete->action();
		echo $LiderzyDelete->getJson();
		break;

	default:
		break;
}