<?php
/**
 * @package crmsw
 * @subpackage ajax
 * @author Piotr Janczura <piotr@janczura.pl>
 * @done 2014-08-29
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

switch ($action) {
	case 'create':
		$StanowiskaCreate = new L_StanowiskaCreate();
		$StanowiskaCreate->fromRequest($HTTP_RAW_POST_DATA);
		$StanowiskaCreate->action();
		echo $StanowiskaCreate->getJson();
		break;
	case 'read':
		$StanowiskaRead = new \crmsw\logic\workplace\Read();
		$StanowiskaRead->fromRequest($_REQUEST);
		$StanowiskaRead->action();
		echo $StanowiskaRead->getJson();
		break;
	case 'update':
		$StanowiskaUpdate = new L_StanowiskaUpdate();
		$StanowiskaUpdate->fromRequest($HTTP_RAW_POST_DATA);
		$StanowiskaUpdate->action();
		echo $StanowiskaUpdate->getJson();
		break;
	case 'delete':
		$StanowiskaDelete = new L_StanowiskaDelete();
		$StanowiskaDelete->fromRequest($HTTP_RAW_POST_DATA);
		$StanowiskaDelete->action();
		echo $StanowiskaDelete->getJson();
		break;

	default:
		break;
}