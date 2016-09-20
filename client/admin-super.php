<?php
/**
 * @package crmsw
 * @subpackage client
 * @author Piotr Janczura <piotr@janczura.pl>
 * @done 2014-09-09
 */
//@session_start();
require_once '../config.php';
require_once '../server/pjpl/error.php';

if( !isset($_SESSION['USER_STATUS']) || $_SESSION['USER_STATUS'] !== CRM::ADMIN_SUPER){
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

    <title>CRM - admin super</title>
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
				Ext.getBody().mask('Start panelu administratora');
		</script>

    <link href="css/index.css" rel="stylesheet" type="text/css" />

		<script src="ext/pjpl/RowsFilter.js"></script>
    <script src="lib/const.js"></script>
		<script src="lib/e.js"></script>
		<script src="lib.foregin/js/underscore-min.js"></script>
		<script src="crm.js"></script>

		<script src="app/model/firmy.model.js"></script>
		<script src="app/model/firmy-oddzialy.model.js"></script>
		<script src="app/model/pracownicy.model.js"></script>
		<script src="app/model/zarzad.model.js"></script>

		<script src="app/store/firmy.store.js"></script>
		<script src="app/store/firmy-oddzialy.store.js"></script>
		<script src="app/store/pracownicy.store.js"></script>
    <script src="app/store/zarzad.store.js" ></script>

		<script src="app/grid/firmy.grid.js"></script>
		<script src="app/grid/firmy-oddzialy.grid.js"></script>
		<script src="app/grid/pracownicy.grid.js"></script>
    <script src="app/grid/zarzad.grid.js" ></script>

		<script src="app/form/zmiana-hasla.form.js"></script>

    <script src="app/win/dane-logowania.window.js" type="text/javascript"></script>
    <script src="app/win/firmy.window.js" type="text/javascript"></script>
		<script src="app/win/help.window.js"></script>
		<script src="app/win/wybor-daty.window.js"></script>

    <script src="app/win/admin/zarzad.window.js" type="text/javascript"></script>

    <script src="app/admin-super.js"></script>

		<script>
				Ext.getBody().unmask();
		</script>
  </body>
</html>
