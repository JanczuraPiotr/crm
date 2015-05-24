<?php
////@session_start();
//require_once '../config.php';
//
//
//
//if(isset($_REQUEST['action'])){
//  $action = $_REQUEST['action'];
//}else{
//  $action = null;
//}
//
//$user_typ = '';
//
//switch ($action){
//
//  case 'zaloguj':
//    $Logowanie = new L_Logowanie();
//    $Logowanie->fromRequest($_REQUEST);
//    $Logowanie->action();
//    echo $Logowanie->getJson();
//    break;
//
//  case 'wyloguj':
//    @session_destroy();
//    echo '{"success":"true", "data":{ "user_typ":"" } }';
//    break;
//
//  case 'get-uset-typ':
//  default:
//    if(isset($_SESSION['USER_TYP'])){
//      echo '{"success":"true", "data":{"user_typ" :"'.$_SESSION['USER_TYP'].'"}}';
//    }else{
//      echo '{"success":"false", "data":{"user_typ":""}  }';
//      //echo '{"user_typ":"ADMIN_SUPER"}';
//    }
//}

?>