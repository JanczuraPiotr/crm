<?php
/**
 * @package crmsw
 * @subpackage ajax
 * @author Piotr Janczura <piotr@janczura.pl>
 * @done 2014-11-04
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
//	case 'create':
//		$StatusZadaniaCreate = new LStatusZadaniaCreate();
//		$StatusZadaniaCreate->fromRequest($HTTP_RAW_POST_DATA);
//		$StatusZadaniaCreate->action();
//		echo $StatusZadaniaCreate->getJson();
//		break;
	case 'read':// @done 2014-11-04
		$StatusZadaniaRead = new \L_StatusZadaniaRead();
		$StatusZadaniaRead->fromRequest($_REQUEST);
		$StatusZadaniaRead->action();
		echo $StatusZadaniaRead->getJson();
		break;
//	case 'update':
//		$StatusZadaniaUpdate = new LStatusZadaniaUpdate();
//		$StatusZadaniaUpdate->fromRequest($HTTP_RAW_POST_DATA);
//		$StatusZadaniaUpdate->action();
//		echo $StatusZadaniaUpdate->getJson();
//		break;
//	case 'delete':
//		$StatusZadaniaDelete = new LStatusZadaniaDelete();
//		$StatusZadaniaDelete->fromRequest($HTTP_RAW_POST_DATA);
//		$StatusZadaniaDelete->action();
//		echo $StatusZadaniaDelete->getJson();
//		break;

	default:
		break;
}