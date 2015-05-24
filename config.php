<?php
/**
 * @package crmswsw
 * @subpackage config
 * @author Piotr Janczura <piotr@janczura.pl>
 * @confirm 2014-08-28
 */
@session_start();

if(gethostname() === 'radmin.nazwa.pl'){
  $_SESSION['SERVER_TYPE'] = 'TEST';
}else{
  $_SESSION['SERVER_TYPE'] = 'DEV';
}

switch($_SESSION['SERVER_TYPE']){

  case 'DEV':
    define('DBHOST',        'localhost');
    define('DBTYPE',        'mysql');
    define('CHARSET',	    'utf8');
    define('DBPORT',        3306);
    define('DBNAME',        'crm');
    define('DBUSER',        'crm');
    define('DBPASS',        'wIl45mSb');
    break;

  case 'TEST':
    define('DBHOST',        'radmin.nazwa.pl');
    define('DBTYPE',        'mysql');
    define('CHARSET',	    'utf8');
    define('DBPORT',        3307);
    define('DBNAME',        'radmin_4');
    define('DBUSER',        'radmin_4');
    define('DBPASS',        'wIl45mSb');
    break;
};

include 'loader.php';

try{
	if(isset($_REQUEST['used_tables'])){
		// Przesłano tabele z identyfikatorami tabel na których pracuje klient i czasy na kiedy klient miał aktualne tabele.
		// Oczekuje więc odpowiedzi czy jakieś zmiany w tych tabelach miały miesce.
		if( ( $modified_tables = \crmsw\lib\db\DB::init(DBTYPE, DBHOST, DBNAME, DBPORT, DBUSER, DBPASS , CHARSET,'grupa', (array)$_REQUEST['used_tables']) ) ){
			if(count($modified_tables) > 0 ){
				echo json_encode(array('ret'=>OK,'msg'=>'','data'=>$modified_tables));
				exit;
			}else{
				echo json_encode(array('ret'=>OK));
				exit;
			}
		}
	}
  \crmsw\lib\db\DB::init(DBTYPE, DBHOST, DBNAME, DBPORT, DBUSER, DBPASS, CHARSET,'grupa');
}catch(Exception $E){
	echo $E->getMessage();
}
