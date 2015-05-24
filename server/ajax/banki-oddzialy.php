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
		$BankiOddzialyCreate = new L_BankiOddzialyCreate();
		$BankiOddzialyCreate->fromRequest($HTTP_RAW_POST_DATA);
		$BankiOddzialyCreate->action();
		echo $BankiOddzialyCreate->getJson();
		break;
	case 'read':
		$BankiOddzialyRead = new L_BankiOddzialyRead();
		$BankiOddzialyRead->fromRequest($_REQUEST);
		$BankiOddzialyRead->action();
		echo $BankiOddzialyRead->getJson();
		break;
	case 'update':
		$BankiOddzialyUpdate = new L_BankiOddzialyUpdate();
		$BankiOddzialyUpdate->fromRequest($HTTP_RAW_POST_DATA);
		$BankiOddzialyUpdate->action();
		echo $BankiOddzialyUpdate->getJson();
		break;
	case 'delete':
		$BankiOddzialyDelete = new L_BankiOddzialyDelete();
		$BankiOddzialyDelete->fromRequest($HTTP_RAW_POST_DATA);
		$BankiOddzialyDelete->action();
		echo $BankiOddzialyDelete->getJson();
		break;

	default:
		break;
}