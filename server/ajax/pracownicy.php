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
		$PracownicyCreate = new L_PracownicyCreate();
		$PracownicyCreate->fromRequest($HTTP_RAW_POST_DATA);
		$PracownicyCreate->action();
		echo $PracownicyCreate->getJson();
		break;
	case 'read':
		$PracownicyRead = new L_PracownicyRead();
		$PracownicyRead->fromRequest($_REQUEST);
		$PracownicyRead->action();
		echo $PracownicyRead->getJson();
		break;
	case 'update':
		$PracownicyUpdate = new L_PracownicyUpdate();
		$PracownicyUpdate->fromRequest($HTTP_RAW_POST_DATA);
		$PracownicyUpdate->action();
		echo $PracownicyUpdate->getJson();
		break;
	case 'delete':
		$PracownicyDelete = new L_PracownicyDelete();
		$PracownicyDelete->fromRequest($HTTP_RAW_POST_DATA);
		$PracownicyDelete->action();
		echo $PracownicyDelete->getJson();
		break;

	default:
		break;
}