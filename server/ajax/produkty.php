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
		$ProduktyCreate = new L_ProduktyCreate();
		$ProduktyCreate->fromRequest($HTTP_RAW_POST_DATA);
		$ProduktyCreate->action();
		echo $ProduktyCreate->getJson();
		break;
	case 'read':
		$ProduktyRead = new L_ProduktyRead();
		$ProduktyRead->fromRequest($_REQUEST);
		$ProduktyRead->action();
		echo $ProduktyRead->getJson();
		break;
	case 'update':
		$ProduktyUpdate = new L_ProduktyUpdate();
		$ProduktyUpdate->fromRequest($HTTP_RAW_POST_DATA);
		$ProduktyUpdate->action();
		echo $ProduktyUpdate->getJson();
		break;
	case 'delete':
		$ProduktyDelete = new L_ProduktyDelete();
		$ProduktyDelete->fromRequest($HTTP_RAW_POST_DATA);
		$ProduktyDelete->action();
		echo $ProduktyDelete->getJson();
		break;

	default:
		break;
}