<?php
namespace crmsw\ajax;
/**
 * @package crmswsw
 * @subpackage ajax
 * @author Piotr Janczura <piotr@janczura.pl>
 * @done 2014-11-04
 */
require_once '../../config.php';
if( !isset($_SESSION['USER_STATUS']) ){
  echo json_encode(array('success'=>FALSE,'code'=> \pjpl\e\a\E::NOT_LOGIN,'msg'=>'ERR_NOT_LOGIN'));
  exit ;
}

if(isset($_REQUEST['action'])){
	$action = $_REQUEST['action'];
}else{
	$action = null;
}

switch ($action) {
	case 'create':// @done 2014-10-31
		$KlienciCreate = new \L_KlienciCreate();
		$KlienciCreate->fromRequest($HTTP_RAW_POST_DATA);
		$KlienciCreate->action();
		echo $KlienciCreate->getJson();
		break;
	case 'read': // @done 2014-10-31
		$KlienciRead = new \crmsw\logic\buyer\Read();
		$KlienciRead->fromRequest($_REQUEST);
		$KlienciRead->action();
		echo $KlienciRead->getJson();
		break;
	case 'update':// @done 2014-10-31
		$KlienciUpdate = new \L_KlienciUpdate();
		$KlienciUpdate->fromRequest($HTTP_RAW_POST_DATA);
		$KlienciUpdate->action();
		echo $KlienciUpdate->getJson();
		break;
	case 'delete':// @done 2014-10-31
		$KlienciDelete = new \L_KlienciDelete();
		$KlienciDelete->fromRequest($HTTP_RAW_POST_DATA);
		$KlienciDelete->action();
		echo $KlienciDelete->getJson();
		break;

	default:
		break;
}