<?php
/**
 * @package crmsw
 * @subpackage ajax
 * @author Piotr Janczura <piotr@janczura.pl>
 */
require_once '../../config.php';
if( !isset($_SESSION['USER_STATUS'])
				|| (
					$_SESSION['USER_STATUS'] != CRM::ADMIN_SUPER &&
					$_SESSION['USER_STATUS'] != CRM::ADMIN_ZWYKLY &&
					$_SESSION['USER_STATUS'] != CRM::ZARZAD_PREZES &&
					$_SESSION['USER_STATUS'] != CRM::ZARZAD_CZLONEK
				)){
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
		$ZarzadCreate = new L_ZarzadCreate();
		$ZarzadCreate->fromRequest($HTTP_RAW_POST_DATA);
		$ZarzadCreate->action();
		echo $ZarzadCreate->getJson();
		break;
	case 'read':
		$ZarzadRead = new L_ZarzadRead();
		$ZarzadRead->fromRequest($_REQUEST);
		$ZarzadRead->action();
		echo $ZarzadRead->getJson();
		break;
	case 'update':
		$ZarzadUpdate = new L_ZarzadUpdate();
		$ZarzadUpdate->fromRequest($HTTP_RAW_POST_DATA);
		$ZarzadUpdate->action();
		echo $ZarzadUpdate->getJson();
		break;
	case 'delete':
		$ZarzadDelete = new L_ZarzadDelete();
		$ZarzadDelete->fromRequest($HTTP_RAW_POST_DATA);
		$ZarzadDelete->action();
		echo $ZarzadDelete->getJson();
		break;

	default:
		break;
}