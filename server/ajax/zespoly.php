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
		$ZespolyCreate = new L_ZespolyCreate();
		$ZespolyCreate->fromRequest($HTTP_RAW_POST_DATA);
		$ZespolyCreate->action();
		echo $ZespolyCreate->getJson();
		break;
	case 'read':
		$ZespolyRead = new L_ZespolyRead();
		$ZespolyRead->fromRequest($_REQUEST);
		$ZespolyRead->action();
		echo $ZespolyRead->getJson();
		break;
	case 'update':
		$ZespolyUpdate = new L_ZespolyUpdate();
		$ZespolyUpdate->fromRequest($HTTP_RAW_POST_DATA);
		$ZespolyUpdate->action();
		echo $ZespolyUpdate->getJson();
		break;
	case 'delete':
		$ZespolyDelete = new L_ZespolyDelete();
		$ZespolyDelete->fromRequest($HTTP_RAW_POST_DATA);
		$ZespolyDelete->action();
		echo $ZespolyDelete->getJson();
		break;

	default:
		break;
}