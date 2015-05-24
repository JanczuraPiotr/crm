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
		$BankiCreate = new L_BankiCreate();
		$BankiCreate->fromRequest($HTTP_RAW_POST_DATA);
		$BankiCreate->action();
		echo $BankiCreate->getJson();
		break;
	case 'read':
		$BankiRead = new L_BankiRead();
		$BankiRead->fromRequest($_REQUEST);
		$BankiRead->action();
		echo $BankiRead->getJson();
		break;
	case 'update':
		$BankiUpdate = new L_BankiUpdate();
		$BankiUpdate->fromRequest($HTTP_RAW_POST_DATA);
		$BankiUpdate->action();
		echo $BankiUpdate->getJson();
		break;
	case 'delete':
		$BankiDelete = new L_BankiDelete();
		$BankiDelete->fromRequest($HTTP_RAW_POST_DATA);
		$BankiDelete->action();
		echo $BankiDelete->getJson();
		break;

	default:
		break;
}