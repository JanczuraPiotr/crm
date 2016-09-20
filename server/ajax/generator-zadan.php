<?php
namespace crmsw\ajax;
/**
 * @package crmsw
 * @subpackage ajax
 * @author Piotr Janczura <piotr@janczura.pl>
 * @done 2014-09-04 Zmiana znaczenia dat i sposobu notowania wykonywanych kroków.
 */
require_once '../../config.php';
if( ! isset($_SESSION['USER_STATUS'])){
  echo json_encode(array('success'=>FALSE,'code'=>ERR_NOT_LOGIN,'msg'=>'ERR_NOT_LOGIN'));
  exit ;
}

if(isset($_REQUEST['action'])){
	$action = $_REQUEST['action'];
}else{
	$action = null;
}

switch ($action) {

	case 'generate':// @done 2014-09-15
		$Generator = new \crmsw\logic\tasks\UnthinkingGenerator();
		echo $Generator->externalCall($_REQUEST);
		break;
//	case 'create':
//		$ZadaniaCreate = new LZadaniaCreate();
//		$ZadaniaCreate->fromRequest($HTTP_RAW_POST_DATA);
//		$ZadaniaCreate->action();
//		echo $ZadaniaCreate->getJson();
//		break;
//	case 'read':
//		$ZadaniaRead = new LZadaniaRead();
//		$ZadaniaRead->fromRequest($_REQUEST);
//		$ZadaniaRead->action();
//		echo $ZadaniaRead->getJson();
//		break;
//	case 'update':
//		$ZadaniaUpdate = new LZadaniaUpdate();
//		$ZadaniaUpdate->fromRequest($HTTP_RAW_POST_DATA);
//		$ZadaniaUpdate->action();
//		echo $ZadaniaUpdate->getJson();
//		break;
//	case 'delete':
//		$ZadaniaDelete = new LZadaniaDelete();
//		$ZadaniaDelete->fromRequest($HTTP_RAW_POST_DATA);
//		$ZadaniaDelete->action();
//		echo $ZadaniaDelete->getJson();
//		break;

	default:
		break;
}