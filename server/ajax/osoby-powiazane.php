<?php
/**
 * @package crmsw
 * @subpackage ajax
 * @author Piotr Janczura <piotr@janczura.pl>
 * @done 2014-11-05
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
	case 'create':// @done 2014-11-05
		$OsobyPowiazaneCreate = new L_OsobyPowiazaneCreate();
		$OsobyPowiazaneCreate->fromRequest($HTTP_RAW_POST_DATA);
		$OsobyPowiazaneCreate->action();
		echo $OsobyPowiazaneCreate->getJson();
		break;
	case 'read':// @done 2014-11-05
		$OsobyPowiazaneRead = new L_OsobyPowiazaneRead();
		$OsobyPowiazaneRead->fromRequest($_REQUEST);
		$OsobyPowiazaneRead->action();
		echo $OsobyPowiazaneRead->getJson();
		break;
	case 'update':// @done 2014-11-05
		$OsobyPowiazaneUpdate = new L_OsobyPowiazaneUpdate();
		$OsobyPowiazaneUpdate->fromRequest($HTTP_RAW_POST_DATA);
		$OsobyPowiazaneUpdate->action();
		echo $OsobyPowiazaneUpdate->getJson();
		break;
	case 'delete':// @done 2014-11-05
		$OsobyPowiazaneDelete = new L_OsobyPowiazaneDelete();
		$OsobyPowiazaneDelete->fromRequest($HTTP_RAW_POST_DATA);
		$OsobyPowiazaneDelete->action();
		echo $OsobyPowiazaneDelete->getJson();
		break;

	default:
		break;
}