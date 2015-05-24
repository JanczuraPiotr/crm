<?php
/**
 * @package crmsw
 * @subpackage config
 * @author Piotr Janczura <piotr@janczura.pl>
 */
require_once 'server/pjpl/time.php';

function classLoader($classname){
	switch($classname){

    case 'CRM':
      require_once 'crm.class.php';
      break;

    //------------------------------------------------------------------------------
    // Tabele bazy danych
    case 'Administratorzy':
    case 'AdministratorzyDI':
    case 'AdministratorzyTable';
      require_once 'server/db/administratorzy.table.php';
      break;

    case 'Banki':
    case 'BankiDI':
    case 'BankiTable':								require_once 'server/db/banki.table.php';	break;

    case 'BankiOddzialy':
    case 'BankiOddzialyDI':
    case 'BankiOddzialyTable':				require_once 'server/db/banki_oddzialy.table.php';	break;

    case 'BankOddzialFirmaOddzial':
    case 'BankiOddzialyFirmyOddzialyDI':
    case 'BankiOddzialyFirmyOddzialyTable':		require_once 'server/db/banki_oddzialy_firmy_oddzialy.table.php';break;

    case 'DokumentProduktu':
    case 'DokumentyProduktuDI':
    case 'DokumentyProduktuTable':		require_once 'server/db/dokumenty_produktu.table.php';break;

    case 'DokumentSlownik':
    case 'DokumentySlownikDI':
    case 'DokumentySlownikTable':			require_once 'server/db/dokumenty_slownik.table.php';	break;

    case 'Firmy':
    case 'FirmyDI':
    case 'FirmyTable':								require_once 'server/db/firmy.table.php';break;

    case 'FirmaOddzial':
    case 'FirmyOddzialyDI':
    case 'FirmyOddzialyTable':			  require_once 'server/db/firmy_oddzialy.table.php';break;

    case 'Klient':
    case 'KlienciDI':
    case 'KlienciTable':							require_once 'server/db/klienci.table.php';	break;

    case 'StatusKlienta':
    case 'StatusKlientaDI':
    case 'StatusyKlientowTable':			require_once 'server/db/statusy_klientow.table.php';	break;

    case 'PochodzenieKlienta':
    case 'PochodzenieKlientaDI':
    case 'PochodzenieKlientowTable':		require_once 'server/db/pochodzenie_klientow.table.php';	break;

    case 'OsobaPowiazana':
    case 'OsobyPowiazaneDI':
    case 'OsobyPowiazaneTable':					require_once 'server/db/osoby_powiazane.table.php';	break;

    case 'Lider':
    case 'LiderzyDI':
    case 'LiderzyTable':
      require_once 'server/db/liderzy.table.php';
      break;

    case 'Pracownicy':
    case 'PracownicyDI':
    case 'PracownicyTable':
			require_once 'server/db/pracownicy.table.php';
      break;

    case 'Produkt':
    case 'ProduktyDI':
    case 'ProduktyTable':
			require_once 'server/db/produkty.table.php';
			break;

    case 'StatusZadania':
    case 'StatusZadaniaDI':
    case 'StatusyZadanTable':
			require_once 'server/db/statusy_zadan.table.php';
			break;


    case 'Stanowiska':
    case 'StanowiskaDI':
    case 'StanowiskaTable':
			require_once 'server/db/stanowiska.table.php';
			break;

    case 'StatusStanowiska':
    case 'StatusStanowiskaDI':
    case 'StatusyStanowiskTable':			require_once 'server/db/statusy_stanowisk.table.php';	break;

    case 'Tabele':
    case 'TabeleDI':
    case 'TabeleTable':
      require_once 'server/db/tabele.table.php';
      break;

    case 'Updates':
    case 'UpdatesDI':
    case 'UpdatesTable':
      require_once 'server/db/updates.table.php';
      break;

    case 'UprawnieniaGrup':
    case 'UprawnieniaGrupDI':
    case 'UprawnieniaGrupTable':
      require_once 'server/db/uprawnienia_grup.table.php';
      break;

    case 'Zadania':
    case 'ZadaniaDI':
    case 'ZadaniaTable':
      require_once 'server/db/zadania.table.php';
      break;

    case 'ZadaniaDokumenty':
    case 'ZadaniaDokumentyDI':
    case 'ZadaniaDokumentyTable':
      require_once 'server/db/zadania_dokumenty.table.php';
      break;

    case 'ZadaniaFirmy':
    case 'ZadaniaFirmyDI':
    case 'ZadaniaFirmyTable':
      require_once 'server/db/zadania_firmy.table.php';
      break;

    case 'ZadaniaOpis':
    case 'ZadaniaOpisyDI':
    case 'ZadaniaOpisyTable':
			require_once 'server/db/zadania_opis.table.php';
			break;

    case 'Zarzadcy':
    case 'ZarzadcyDI':
    case 'ZarzadcyTable':
      require_once 'server/db/zarzadcy.table.php';
      break;

    case 'Zarzad':
    case 'ZarzadyDI':
    case 'ZarzadyTable':							require_once 'server/db/zarzady.table.php';	break;

    case 'Zatrudnienie':
    case 'ZatrudnieniaDI':
		case 'ZatrudnieniaTable':					require_once 'server/db/zatrudnienia.table.php'; break;

    case 'Zespoly':
    case 'ZespolyDI':
    case 'ZespolyTable':
      require_once 'server/db/zespoly.table.php';
      break;

		default :
			// @todo Przenieść dołącznie definicji z powyższych casów do poniższego kodu
			$in = explode('\\',$classname);
			if(count($in) > 1){
				// Wykonywane tylko dla nazw klas umieszczonych w namespace
				$path = '';
				if( $toc = array_shift($in) ) {
					switch ($toc){
						case 'crmsw':
							$path = implode('/', $in);
							$path = 'server/'.implode('/', $in).'.php';
							require $path;
							break;
						case 'pjpl':
							switch ( $ns = array_shift($in) ){
								case'depreciate':
									require "server/pjpl/gold.php";
									require 'server/pjpl/error.php';
									require 'server/pjpl/lib.php';
									break;
								default :
									array_unshift($in, $ns);
									// @todo Po usunięciu plików zdeprecjonowanych tylko dwa poniższe wiersze mają pozostać w case 'pjpl'
									$path = 'server/pjpl/'.implode('/', $in).'.php';
									require $path;
							}
							break;
					}
				}
				return;
			}else{
				if(substr($classname, 0, 2) === 'L_'){
					require 'server/logic/'.$classname.'.php';
					return;
				}
			}
  }


}
spl_autoload_register('classLoader');

?>