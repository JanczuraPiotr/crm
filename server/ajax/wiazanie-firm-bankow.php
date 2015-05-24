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
		$WiazanieFirmBankowCreate = new L_WiazanieFirmBankowCreate();
		$WiazanieFirmBankowCreate->fromRequest($HTTP_RAW_POST_DATA);
		$WiazanieFirmBankowCreate->action();
		echo $WiazanieFirmBankowCreate->getJson();
		break;
	case 'read':
		$WiazanieFirmBankowRead = new L_WiazanieFirmBankowRead();
		$WiazanieFirmBankowRead->fromRequest($_REQUEST);
		$WiazanieFirmBankowRead->action();
		echo $WiazanieFirmBankowRead->getJson();
		break;
	case 'delete':
		$WiazanieFirmBankowDelete = new L_WiazanieFirmBankowDelete();
		$WiazanieFirmBankowDelete->fromRequest($HTTP_RAW_POST_DATA);
		$WiazanieFirmBankowDelete->action();
		echo $WiazanieFirmBankowDelete->getJson();
		break;

	default:
		break;
}