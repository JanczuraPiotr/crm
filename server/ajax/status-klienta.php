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
		$StatusKlientaCreate = new L_StatusKlientaCreate();
		$StatusKlientaCreate->fromRequest($HTTP_RAW_POST_DATA);
		$StatusKlientaCreate->action();
		echo $StatusKlientaCreate->getJson();
		break;
	case 'read':
		$StatusKlientaRead = new L_StatusKlientaRead();
		$StatusKlientaRead->fromRequest($_REQUEST);
		$StatusKlientaRead->action();
		echo $StatusKlientaRead->getJson();
		break;
	case 'update':
		$StatusKlientaUpdate = new L_StatusKlientaUpdate();
		$StatusKlientaUpdate->fromRequest($HTTP_RAW_POST_DATA);
		$StatusKlientaUpdate->action();
		echo $StatusKlientaUpdate->getJson();
		break;
	case 'delete':
		$StatusKlientaDelete = new L_StatusKlientaDelete();
		$StatusKlientaDelete->fromRequest($HTTP_RAW_POST_DATA);
		$StatusKlientaDelete->action();
		echo $StatusKlientaDelete->getJson();
		break;

	default:
		break;
}