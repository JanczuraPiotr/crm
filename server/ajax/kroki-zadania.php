<?php
namespace crmsw\ajax;
/**
 * @package crmsw
 * @subpackage ajax
 * @author Piotr Janczura <piotr@janczura.pl>
 * @done 2014-08-28
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

	case 'create':
		$NextStep = new \crmsw\logic\tasks\NextStep();
		echo $NextStep->externalCall($_REQUEST);
		break;

	case 'read':
		$ReadTaskStages = new \crmsw\logic\tasks\ReadStages();
		$ReadTaskStages->fromRequest($_REQUEST);
		$ReadTaskStages->action();
		echo $ReadTaskStages->getJson();
		break;

	case 'do-lidera':
		$TransferToLeader = new \crmsw\logic\tasks\TransferFromSubordinateToLeader();
		$TransferToLeader->fromRequest($_REQUEST);
		$TransferToLeader->action();
		echo $TransferToLeader->getJson();
		break;

	case 'do-podwladnego':
		$TransferToSubordinate = new \crmsw\logic\tasks\TransferFromLeaderToSubordinate();
		$TransferToSubordinate->fromRequest($_REQUEST);
		$TransferToSubordinate->action();
		echo $TransferToSubordinate->getJson();
		break;

	case 'do-lidera-kooperanta':
		// @todo zamienić na "do-kooperanta" jako operacja możliwa tylko między liderami
		$ZadaniaDoInnegoLidera = new \crmsw\logic\tasks\TransferLeaderToLeader();
		$ZadaniaDoInnegoLidera->fromRequest($_REQUEST);
		$ZadaniaDoInnegoLidera->action();
		echo $ZadaniaDoInnegoLidera->getJson();
		break;

	default:
		break;
}