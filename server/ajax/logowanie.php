<?php
require_once '../../config.php';

if(isset($_REQUEST['action'])){
  $action = $_REQUEST['action'];
}else{
  $action = null;
}

switch ($action){

  case 'zaloguj':
    $Logowanie = new L_Logowanie();
    $Logowanie->fromRequest($_REQUEST);
    $Logowanie->action();
    echo $Logowanie->getJson();
    break;

  case 'wyloguj':
    @session_destroy();
    echo '{"success":"true", "data":{ "user_typ":"" } }';
    break;

  case 'get-uset-typ':
  default:
    if(isset($_SESSION['USER_STATUS'])){
      echo	'{"success":"true", "data" : {'
							.'"user_status" :"'.(int)$_SESSION['USER_STATUS']
							.'" , "firma_id" : "'.(int)$_SESSION['FIRMA_ID']
							.'" , "placowka_id":"'.(int)$_SESSION['PLACOWKA_ID']
							.'" , "pracownik_id":"'.(int)$_SESSION['PRACOWNIK_ID']
							.'" , "pracownik_nazwa":"'.(string)$_SESSION['PRACOWNIK_NAZWA']
							.'" , "stanowisko_id" : "'.(int)$_SESSION['STANOWISKO_ID']
						.'"}}';
    }else{
      echo '{"success":"false", "data":{"user_status":""}  }';
      //echo '{"user_typ":"ADMIN_SUPER"}';
    }
}
