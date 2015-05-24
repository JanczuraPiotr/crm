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
		$DokumentySlownikCreate = new L_DokumentySlownikCreate();
		$DokumentySlownikCreate->fromRequest($HTTP_RAW_POST_DATA);
		$DokumentySlownikCreate->action();
		echo $DokumentySlownikCreate->getJson();
		break;
	case 'read':
		$DokumentySlownikRead = new L_DokumentySlownikRead();
		$DokumentySlownikRead->fromRequest($_REQUEST);
		$DokumentySlownikRead->action();
		echo $DokumentySlownikRead->getJson();
		break;
	case 'update':
		$DokumentySlownikUpdate = new L_DokumentySlownikUpdate();
		$DokumentySlownikUpdate->fromRequest($HTTP_RAW_POST_DATA);
		$DokumentySlownikUpdate->action();
		echo $DokumentySlownikUpdate->getJson();
		break;
	case 'delete':
		$DokumentySlownikDelete = new L_DokumentySlownikDelete();
		$DokumentySlownikDelete->fromRequest($HTTP_RAW_POST_DATA);
		$DokumentySlownikDelete->action();
		echo $DokumentySlownikDelete->getJson();
		break;

	default:
		break;
}