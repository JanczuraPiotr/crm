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
		$ZadaniaOpisCreate = new \crmsw\logic\tasks\CreateDescription();
		$ZadaniaOpisCreate->fromRequest($HTTP_RAW_POST_DATA);
		$ZadaniaOpisCreate->action();
		echo $ZadaniaOpisCreate->getJson();
		break;
	case 'read':
		$ReadDescriptions = new \crmsw\logic\tasks\ReadDescription();
		$ReadDescriptions->fromRequest($_REQUEST);
		$ReadDescriptions->action();
		echo $ReadDescriptions->getJson();
		break;
//	case 'update':
//		$ZadaniaOpisUpdate = new LZadaniaOpisUpdate();
//		$ZadaniaOpisUpdate->fromRequest($HTTP_RAW_POST_DATA);
//		$ZadaniaOpisUpdate->action();
//		echo $ZadaniaOpisUpdate->getJson();
//		break;
//	case 'delete':
//		$ZadaniaOpisDelete = new LZadaniaOpisDelete();
//		$ZadaniaOpisDelete->fromRequest($HTTP_RAW_POST_DATA);
//		$ZadaniaOpisDelete->action();
//		echo $ZadaniaOpisDelete->getJson();
//		break;

	default:
		break;
}