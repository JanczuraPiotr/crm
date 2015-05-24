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
		$PochodzenieKlientaCreate = new L_PochodzenieKlientaCreate();
		$PochodzenieKlientaCreate->fromRequest($HTTP_RAW_POST_DATA);
		$PochodzenieKlientaCreate->action();
		echo $PochodzenieKlientaCreate->getJson();
		break;
	case 'read':
		$PochodzenieKlientaRead = new L_PochodzenieKlientaRead();
		$PochodzenieKlientaRead->fromRequest($_REQUEST);
		$PochodzenieKlientaRead->action();
		echo $PochodzenieKlientaRead->getJson();
		break;
	case 'update':
		$PochodzenieKlientaUpdate = new L_PochodzenieKlientaUpdate();
		$PochodzenieKlientaUpdate->fromRequest($HTTP_RAW_POST_DATA);
		$PochodzenieKlientaUpdate->action();
		echo $PochodzenieKlientaUpdate->getJson();
		break;
	case 'delete':
		$PochodzenieKlientaDelete = new L_PochodzenieKlientaDelete();
		$PochodzenieKlientaDelete->fromRequest($HTTP_RAW_POST_DATA);
		$PochodzenieKlientaDelete->action();
		echo $PochodzenieKlientaDelete->getJson();
		break;

	default:
		break;
}