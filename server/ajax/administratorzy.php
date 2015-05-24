<?php
/**
 * @package crmsw
 * @subpackage ajax
 * @author Piotr Janczura <piotr@janczura.pl>
 */
require_once '../../config.php';
if( !isset($_SESSION['USER_STATUS']) || $_SESSION['USER_STATUS'] !== CRM::ADMIN_SUPER){
	@session_destroy();
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
		$AdministratorCreate = new L_AdministratorzyCreate();
		$AdministratorCreate->fromRequest($HTTP_RAW_POST_DATA);
		$AdministratorCreate->action();
		echo $AdministratorCreate->getJson();
		break;
	case 'read':
		$AdmininistratorRead = new L_AdministratorzyRead();
		$AdmininistratorRead->fromRequest($_REQUEST);
		$AdmininistratorRead->action();
		echo $AdmininistratorRead->getJson();
		break;
	case 'update':
		$AdmininistrtorUpdate = new L_AdministratorzyUpdate();
		$AdmininistrtorUpdate->fromRequest($HTTP_RAW_POST_DATA);
		$AdmininistrtorUpdate->action();
		echo $AdmininistrtorUpdate->getJson();
		break;
	case 'delete':
		$AdministratorDelete = new L_AdministratorzyDelete();
		$AdministratorDelete->fromRequest($HTTP_RAW_POST_DATA);
		$AdministratorDelete->action();
		echo $AdministratorDelete->getJson();
		break;

	default:
		break;
}