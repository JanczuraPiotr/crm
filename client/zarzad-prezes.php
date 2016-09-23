<?php
/**
 * @package crm
 * @subpackage client
 * @author Piotr Janczura <piotr@janczura.pl>
 * @done 4.2.0
 */
require_once '../config.php';
require_once '../server/pjpl/error.php';
if( ! isset($_SESSION['USER_STATUS']) || $_SESSION['USER_STATUS'] === CRM::ZARZAD_PREZES){
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

    <title>CRM - prezes zarządu</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta http-equiv="Expires" content="-1" />
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate" />
    <meta http-equiv="Cache-Control" content="post-check=0, pre-check=0" />
    <meta http-equiv="Pragma" content="no-cache" />

		<link href="ext/resources/ext-theme-classic/ext-theme-classic-all.css" rel="stylesheet" type="text/css" />
		<script src="ext/ext-all.js" ></script>
		<script src="ext/locale/ext-lang-pl.js"></script>

  </head>
  <body>

		<script>
				Ext.getBody().mask('Start panelu prezesa zarządu');
		</script>

    <link href="css/index.css" rel="stylesheet" type="text/css" />
		<script src="ext/pjpl/RowsFilter.js"></script>
    <script src="lib/const.js"></script>
		<script src="lib.foregin/js/underscore-min.js"></script>
		<script src="lib/e.js"></script>
		<script src="crm.js"></script>
		<script src="extcrm.js"></script>

    <script src="app/zarzad-prezes.app.js"></script>

		<script>
				Ext.getBody().unmask();
		</script>
		
  </body>
</html>
