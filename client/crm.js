var CRM = new function(){
	var CRM = this;
	// Administratorzy
	CRM.ADMIN_SUPER									= 1;
	CRM.str_ADMIN_SUPER							= 'ADMIN_SUPER';
  CRM.ADMIN_ZWYKLY                = 2;
  CRM.str_ADMIN_ZWYKLY            = 'ADMIN_ZWYKLY';

	// Kody stanowisk pracy w firmach. Są odzwierciedleniem kolomny kod w tabeli status_stanowiska
  CRM.ZARZAD_PREZES               = 3;
  CRM.str_ZARZAD_PREZES           = 'ZARZAD_PREZES';
  CRM.ZARZAD_CZLONEK              = 4;
  CRM.str_ZARZAD_CZLONEK          = 'ZARZAD_CZLONEK';
  CRM.PRACOWNIK_KIEROWNIK         = 5;
  CRM.str_PRACOWNIK_KIEROWNIK     = 'PRACOWNIK_KIEROWNIK';
  CRM.PRACOWNIK_LIDER             = 6;
  CRM.str_PRACOWNIK_LIDER         = 'PRACOWNIK_LIDER';
  CRM.PRACOWNIK_ZWYKLY            = 7;
  CRM.str_PRACOWNIK_ZWYKLY        = 'PRACOWNIK_ZWYKLY';

	CRM.firma_id										= null;
	CRM.user_status									= null;
	CRM.stanowisko_id								= null;
	CRM.pracownik_id								= null;
	CRM.pracownik_nazwa							= null;

	return CRM;
};