<?php
/**
 * @package crmsw
 * @author Piotr Janczura <piotr@janczura.pl>
 */

class CRM {

	// Administratorzy
	const ADMIN_SUPER                 = 0x00000001;
  const str_ADMIN_SUPER             = 'ADMIN_SUPER';
  const ADMIN_ZWYKLY                = 0x00000002;
  const str_ADMIN_ZWYKLY            = 'ADMIN_ZWYKLY';

	// Kody stanowisk pracy w firmach. Są odzwierciedleniem kolomny kod w tabeli status_stanowiska
  const ZARZAD_PREZES               = 0x00000003;
  const str_ZARZAD_PREZES           = 'ZARZAD_PREZES';
  const ZARZAD_CZLONEK              = 0x00000004;
  const str_ZARZAD_CZLONEK          = 'ZARZAD_CZLONEK';
  const PRACOWNIK_KIEROWNIK         = 0x00000005;
  const str_PRACOWNIK_KIEROWNIK     = 'PRACOWNIK_KIEROWNIK';
  const PRACOWNIK_LIDER             = 0x00000006;
  const str_PRACOWNIK_LIDER         = 'PRACOWNIK_LIDER';
  const PRACOWNIK_ZWYKLY            = 0x00000007;
  const str_PRACOWNIK_ZWYKLY        = 'PRACOWNIK_ZWYKLY';

}