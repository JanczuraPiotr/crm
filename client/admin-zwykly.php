<?php
/**
 * @package crmsw
 * @subpackage client
 * @author Piotr Janczura <piotr@janczura.pl>
 * @confirm 2014-09-09
 */
@session_start();
require_once '../server/pjpl/error.php';
if( !isset($_SESSION['USER_TYP']) || $_SESSION['USER_TYP'] !== 'ADMIN_ZWYKLY'){
  echo json_encode(array('success'=>ERR_NOT_LOGIN,'msg'=>'','data'=>  array()));
  exit ;
}

?>


<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>CRM - admin zwykly</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta http-equiv="Expires" content="-1" />
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate" />
    <meta http-equiv="Cache-Control" content="post-check=0, pre-check=0" />
    <meta http-equiv="Pragma" content="no-cache" />
  </head>
  <body>
<!--		<link href="ext/4.2.2/resources/css/ext-all.css" rel="stylesheet" type="text/css" />

		<script src="ext/4.2.2/ext-all.js" ></script>
		<script src="ext/external/FilterRow.js"></script>

		<script src="ext/4.2.2/locale/ext-lang-pl.js"></script>-->

		<link href="ext/5.0.1/resources/ext-all.css" rel="stylesheet" type="text/css" />
		<script src="ext/5.0.1/ext-all-debug.js" ></script>
		<script src="ext/5.0.1/locale/ext-locale-pl.js"></script>
		<script>
			Ext.getBody().mask('Start panelu administratora');
		</script>
    <link href="css/index.css" rel="stylesheet" type="text/css" />
		<script src="ext/pjpl/RowsFilter.js"></script>
    <script src="lib/const.js"></script>
		<script src="lib.foregin/js/underscore-min.js"></script>
		<script src="crm.js"></script>

    <script src="app/admin-zwykly.js"></script>

		<script>
				Ext.getBody().unmask();
		</script>
	</body>
</html>
