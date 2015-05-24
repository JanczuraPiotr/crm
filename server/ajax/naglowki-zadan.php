<?php
/**
 * @package crmsw
 * @subpackage ajax
 * @author Piotr Janczura <piotr@janczura.pl>
 * @confirm 2014-09-03
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

switch ($action) {

	case 'read':
		$RN = new \crmsw\logic\tasks\ReadHeadlines();
		$RN->fromRequest($_REQUEST);
		$RN->action();
		echo $RN->getJson();
		break;

	default:
		break;
}