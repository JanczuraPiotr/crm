<?php
namespace crm;
/**
 * @package crmsw
 * @subpackage client
 * @author Piotr Janczura <piotr@janczura.pl>
 */
require_once '../config.php';
require_once '../server/pjpl/error.php';
if( !isset($_SESSION['USER_STATUS']) /* || $_SESSION['USER_STATUS'] !== CRM::PRACOWNIK_ZWYKLY */ ){
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

    <title>CRM - pracownik</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta http-equiv="Expires" content="-1" />
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate" />
    <meta http-equiv="Cache-Control" content="post-check=0, pre-check=0" />
    <meta http-equiv="Pragma" content="no-cache" />
  </head>
  <body>
<!--		<link href="ext/5.0.1/resources/ext-all.css" rel="stylesheet" type="text/css" />
		<script src="ext/5.0.1/ext-all-debug.js" ></script>
		<script src="ext/5.0.1/locale/ext-locale-pl.js"></script>-->

		<link href="ext/5.1.0/resources/ext-all.css" rel="stylesheet" type="text/css" />
		<script src="ext/5.1.0/ext-all.js" ></script>
		<script src="ext/5.1.0/locale/ext-locale-pl.js"></script>

		<script>
				Ext.getBody().mask('Start panelu lidera grupy');
		</script>

    <link href="css/index.css" rel="stylesheet" type="text/css" />
		<script src="ext/pjpl/RowsFilter.js"></script>
    <script src="lib/const.js"></script>
		<script src="lib.foregin/js/underscore-min.js"></script>
		<script src="crm.js"></script>

    <script src="app/win/dane-logowania.window.js" type="text/javascript"></script>

		<script src="app/model/banki.model.js"></script>
		<script src="app/model/banki-oddzialy.model.js"></script>
		<script src="app/model/dokumenty-produktu.model.js"></script>
		<script src="app/model/dokumenty-zadania.model.js"></script>
		<script src="app/model/firmy.model.js"></script>
		<script src="app/model/firmy-oddzialy.model.js"></script>
		<script src="app/model/generator-zadan.model.js"></script>
		<script src="app/model/klienci.model.js"></script>
		<script src="app/model/kroki-zadania.model.js"></script>
		<script src="app/model/osoby-powiazane.model.js"></script>
		<script src="app/model/pochodzenie-klientow.model.js"></script>
		<script src="app/model/pracownicy.model.js"></script>
		<script src="app/model/produkty.model.js"></script>
		<script src="app/model/slownik-dokumentow.model.js"></script>
		<script src="app/model/stanowiska.model.js"></script>
		<script src="app/model/status-klienta.model.js"></script>
		<script src="app/model/status-stanowiska.model.js"></script>
		<script src="app/model/status-zadania.model.js"></script>
		<script src="app/model/wiazanie-firm-bankow.model.js"></script>
		<script src="app/model/zadania-naglowek.model.js"></script>
		<script src="app/model/zadania-opis.model.js"></script>
		<script src="app/model/zarzad.model.js"></script>
		<script src="app/model/zatrudnianie.model.js"></script>

		<script src="app/store/banki.store.js"></script>
		<script src="app/store/banki-oddzialy.store.js"></script>
		<script src="app/store/dokumenty-produktu.store.js"></script>
		<script src="app/store/dokumenty-zadania.store.js"></script>
		<script src="app/store/firmy.store.js"></script>
		<script src="app/store/firmy-oddzialy.store.js"></script>
		<script src="app/store/generator-zadan.store.js"></script>
		<script src="app/store/klienci.store.js"></script>
		<script src="app/store/kroki-zadania.store.js"></script>
		<script src="app/store/osoby-powiazane.store.js"></script>
		<script src="app/store/oddzialy-niepowiazane.store.js"></script>
		<script src="app/store/oddzialy-powiazane.store.js"></script>
		<script src="app/store/pochodzenie-klietnow.store.js"></script>
		<script src="app/store/pracownicy.store.js"></script>
		<script src="app/store/produkty.store.js"></script>
		<script src="app/store/slownik-dokumentow.store.js"></script>
		<script src="app/store/stanowiska.store.js"></script>
		<script src="app/store/stanowiska-podleglej-grupy.store.js"></script>
		<script src="app/store/status-klienta.store.js"></script>
		<script src="app/store/status-stanowiska.store.js"></script>
		<script src="app/store/status-zadania.store.js"></script>
		<script src="app/store/wiazanie-firm-bankow.store.js"></script>
		<script src="app/store/zadania-naglowek.store.js"></script>
		<script src="app/store/zadania-naglowek-przydzial.store.js"></script>
		<script src="app/store/zadania-opis.store.js"></script>
		<script src="app/store/zarzad.store.js" ></script>
		<script src="app/store/zatrudnianie.store.js"></script>

		<script src="app/grid/banki.grid.js"></script>
		<script src="app/grid/banki-min.grid.js"></script>
		<script src="app/grid/banki-oddzialy.grid.js"></script>
		<script src="app/grid/banki-oddzialy-min.grid.js"></script>
		<script src="app/grid/dokumenty-produktu.grid.js"></script>
		<script src="app/grid/dokumenty-zadania.grid.js"></script>
		<script src="app/grid/firma.grid.js"></script>
		<script src="app/grid/firmy-min.grid.js"></script>
		<script src="app/grid/firmy-oddzialy.grid.js"></script>
		<script src="app/grid/firmy-oddzialy-min.grid.js"></script>
		<script src="app/grid/generator-zadan.grid.js"></script>
		<script src="app/grid/kroki-zadania.grid.js"></script>
		<script src="app/grid/klienci.grid.js"></script>
		<script src="app/grid/naglowki-zadan-procedowanych-klienta.grid.js"></script>
		<script src="app/grid/oddzialy-niepowiazane.grid.js"></script>
		<script src="app/grid/oddzialy-powiazane.grid.js"></script>
		<script src="app/grid/osoby-powiazane.grid.js"></script>
		<script src="app/grid/pochodzenie-klientow.grid.js"></script>
		<script src="app/grid/pracownicy.grid.js"></script>
		<script src="app/grid/pracownicy-min.grid.js"></script>
		<script src="app/grid/produkty.grid.js"></script>
		<script src="app/grid/przydzial-zadan.grid.js"></script>
		<script src="app/grid/stanowiska-przydzial-zadan.grid.js"></script>
		<script src="app/grid/status-klienta.grid.js"></script>
		<script src="app/grid/status-stanowiska.grid.js"></script>
		<script src="app/grid/status-zadania.grid.js"></script>
		<script src="app/grid/slownik-dokumentow.grid.js"></script>
		<script src="app/grid/stanowiska.grid.js"></script>
		<script src="app/grid/wiazanie-firm-bankow.grid.js"></script>
		<script src="app/grid/zadania-naglowek.grid.js"></script>
		<script src="app/grid/zadania-naglowek-mini.grid.js"></script>
		<script src="app/grid/zatrudnianie.grid.js"></script>
		<script src="app/grid/zarzad.grid.js" ></script>

		<script src="app/form/generator-zadan.form.js"></script>
		<script src="app/form/produkty.form.js"></script>
		<script src="app/form/zmiana-hasla.form.js"></script>


		<script src="app/panel/akta-sprawy.panel.js"></script>
		<script src="app/panel/produkty.panel.js"></script>
		<script src="app/panel/zadania-procedowane-klienta.panel.js"></script>

		<script src="app/view/akta-sprawy.view.js"></script>


		<script src="app/win/generator-zadan.window.js"></script>
		<script src="app/win/help.window.js"></script>
		<script src="app/win/klient.form.js"></script>
		<script src="app/win/klienci.window.js"></script>
		<script src="app/win/next-step.window.js"></script>
		<script src="app/win/przydzial-zadan-sobie.window.js"></script>
		<script src="app/win/slownik-dokumentow.window.js"></script>
		<script src="app/win/status-stanowiska.window.js"></script>
		<script src="app/win/status-zadania.window.js"></script>
		<script src="app/win/wybor-daty.window.js"></script>
		<script src="app/win/wybor-oddzialu-banku.window.js"></script>
		<script src="app/win/zadania-procedowane-klienta.window.js"></script>
		<script src="app/win/zatrudnianie.window.js"></script>
    <script src="app/win/dane-logowania.window.js" type="text/javascript"></script>

		<script src="app/win/pracownik/praca-z-zadaniami.window.js"></script>
		<script src="app/win/pracownik/przekaz-zadanie.window.js"></script>

    <script src="app/pracownik-zwykly.js"></script>

		<script>
				Ext.getBody().unmask();
		</script>
  </body>
</html>
