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
	case 'read':
		$StanowiskaPodleglejGrupyRead = new L_StanowiskaPodleglejGrupyRead();
		$StanowiskaPodleglejGrupyRead->fromRequest($_REQUEST);
		$StanowiskaPodleglejGrupyRead->action();
		echo $StanowiskaPodleglejGrupyRead->getJson();
		break;

	default:
		break;
}