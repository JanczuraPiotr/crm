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

switch ($action) {

	case 'read':
		$StanowiskaZespoluRead = new \crmsw\logic\teams\ReadTeamsWorkplace();
		$StanowiskaZespoluRead->fromRequest($_REQUEST);
		$StanowiskaZespoluRead->action();
		echo $StanowiskaZespoluRead->getJson();
		break;


	default:
		break;
}