<?php
/**
 * @package crmsw
 * @subpackage client
 * @author Piotr Janczura <piotr@janczura.pl>
 */
require_once '../config.php';
require_once '../server/pjpl/error.php';
if( !isset($_SESSION['USER_TYP']) || $_SESSION['USER_TYP'] !== 'PRACOWNIK_KIEROWNIK'){
	@session_destroy();
  echo json_encode(array('success'=>ERR_NOT_LOGIN,'msg'=>'','data'=>  array()));
 ?>
<script language="JavaScript" type="text/javascript">
    location.href="../index.php";
</script>
<?php
  exit();
}

?>


<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>CRM - Kierownik placówki</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta http-equiv="Expires" content="-1" />
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate" />
    <meta http-equiv="Cache-Control" content="post-check=0, pre-check=0" />
    <meta http-equiv="Pragma" content="no-cache" />

		<link href="ext/4.2/resources/ext-theme-classic/ext-theme-classic-all.css" rel="stylesheet" type="text/css" />
		<script src="ext/4.2/ext-all.js" ></script>
		<script src="ext/4.2/locale/ext-lang-pl.js"></script>

<!--
		<link href="client/ext/5.1.0/resources/ext-all.css" rel="stylesheet" type="text/css" />
		<script src="client/ext/5.1.0/ext-all.js" ></script>
		<script src="client/ext/5.1.0/locale/ext-locale-pl.js"></script>
-->

  </head>
  <body>

		<script>
				Ext.getBody().mask('Start panelu kierownika oddziału');
		</script>

    <link href="css/index.css" rel="stylesheet" type="text/css" />
		<script src="ext/pjpl/RowsFilter.js"></script>
    <script src="lib/const.js"></script>
		<script src="lib.foregin/js/underscore-min.js"></script>
		<script src="crm.js"></script>

    <script src="app/win/dane-logowania.window.js" type="text/javascript"></script>

    <script src="app/pracownik-kierownik.js"></script>

		<script>
				Ext.getBody().unmask();
		</script>
	</body>
</html>
