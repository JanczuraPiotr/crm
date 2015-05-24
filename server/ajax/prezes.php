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
		$PrezesCreate = new L_PrezesZarzaduCreate();
		$PrezesCreate->fromRequest($_REQUEST);
		$PrezesCreate->action();
		echo $PrezesCreate->getJson();
		break;
	case 'read':
		$PrezesRead = new L_PrezesZarzaduRead();
		$PrezesRead->fromRequest($_REQUEST);
		$PrezesRead->action();
		echo $PrezesRead->getJson();
		break;

	default:
		break;
}