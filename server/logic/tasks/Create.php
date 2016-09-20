<?php
namespace crmsw\logic\tasks;
use crmsw\lib\a\beta\BusinessLogic;
use pjpl\db\Where;
/**
 * Tworzy zadanie polegające na sprzedaży wybranego produkty wybranemu klientowi.
 *
 * Podczas swojej pracy zakłada dwa rekordy.<br>
 *	-	W Pierwszym jest informacja o sposobie utworzenia zadania a daty: data_next_step = data_step = data(Y-m-d H:i);
 *	-	W drugim rekordzie data_next_step wskazuje na datę kiedy upływa termin wykonania zadania. Pole z notatką jest puste data_step jest pusta.
 * Na wejściu klasa musi otrzymać :
 *	- klient_id	- identyfikator klienta dla którego tworzone jest zadanie
 *	- produkt_id - identyfikator produktu który ma być sprzedany
 *	- stanowisko_id - nie jest wymagane i wówczas zadanie nie jest przydzielone żadnemu pracownikowi ale gdy podane to zadanie będzie przypisane do tego sanowiska
 *	- notatka - informacja o sposobie utworzenia zadania (notatka pozwala opisać każdy krok zadania)
 *	- opis - nie wymagane ale gdy podane to do akt utworzonego zadania dodany będzie wpis z tej zmiennej (pozwala gromadzić informację na temat przebiegu zadania)
 *	- data_next_step - data do kiedy należy zająć się zadaniem
 *
 * @package crmsw
 * @subpackage logic
 * @author Piotr Janczura <piotr@janczura.pl>
 * @done 2014-12-20
 * @doc 2014-12-20
 */
class Create extends BusinessLogic{
	public function __construct() {
		parent::__construct();
	}
	protected function _action() {
		foreach ($this->arguments as $key => $Encja) {
			$this->_algorithm($Encja);
		}
	}
	protected function _algorithm(& $Encja) {
		try{
			$stmt = $this->DB->query('SELECT nr FROM zadania_var');
			$nr_zadania = (int)$stmt->fetchColumn();
			$Encja->nr_zadania = $nr_zadania;

			// Tworze rekord uruchamiający zadanie.
			// Krok będzie oznaczony jako wykonany z czasem utworzenia rekordu.
			// W uwagach do kroku zadania znajdzie się informcja o sposobie utworzenia zadania.
			$eNewZadanie = \Zadanie::create($Encja->toArray());
			$eNewZadanie->status_zadania_id = 0;
			$eNewZadanie->data_next_step = $this->teraz;
			$eNewZadanie->data_step = $this->teraz;

			// Tworzę rekord opisujący konieczność wykonania kroku.
			// Ustawiam datę kiedy powinien być wykonanny następny krok.
			// Data wykonania kroku i uwaga do kroku zostają nie ustawione.
			// Będą uzupełnione w momęcie faktycznego wykonania kroku.
			$eHeadZadanie = \Zadanie::create($eNewZadanie->toArray());
			$eHeadZadanie->notatka = null;
			$eHeadZadanie->data_step = null;
			if( $eHeadZadanie->data_next_step === NULL){
				$eHeadZadanie->data_next_step = $this->data_next_step;
			}

			// Trzeba pobrać listę dokumentów które są wymagane do sprzedania produktu
			// i na jej podstawie przygotować listę kontrolną dostarczonych dokumnetów
			$this->tDokumentyProduktu->where(new Where([
					[
							'attribute' => 'produkt_id',
							'operator'  => '=',
							'value'     => $Encja->produkt_id
					]
			]))->load();
			$eDokumentyArray = array(); // Lista kontrolna dokumentów
			for($rDokumentWymagany = $this->tDokumentyProduktu->getRecordFirst(); $rDokumentWymagany !== null; $rDokumentWymagany = $this->tDokumentyProduktu->getRecordNext()){
				$eDokumentyArray[] = \ZadanieDokument::create([
						'slownik_id'        => $rDokumentWymagany->getEncja()->getSlownikId(),
						'nr_zadania'        => $nr_zadania,
						'adnotacje'         => NULL,
						'data_dostarczenia' => NULL
				]);
			}

			$this->DB->beginTransaction();
			$this->tZadania->createRecordImmediately($eNewZadanie);
			$zadanie_id = $this->tZadania->createRecordImmediately($eHeadZadanie)->getId();
			if(isset($this->arguments['opis']) && !empty($this->arguments['opis'])){
				$this->tZadanieOpisy->createRecordImmediately(new \ZadanieOpis($nr_zadania, $this->arguments['opis']));
			}
			foreach ($eDokumentyArray as $key => $eDokument) {
				$this->tDokumentyZadania->createRecordImmediately($eDokument);
			}
			$this->dataOut[$key] = array('id'=> $zadanie_id ,'zadanie'=>$nr_zadania);
			$this->DB->query('UPDATE zadania_var SET nr = nr + 1');
			$this->DB->commit();
		} catch (\Exception $E) {
			$this->DB->rollBack();
			$this->success = false;
			$this->catchException($E);
		}
	}
	protected function _externalArguments(&$set_request) {
    if(isset($request['data'])){
      foreach ($request as $key => $value) {
				$this->arguments[$key] = \Zadanie::create(array(
								'klient_id'      => $this->Firewall->int($value['klient_id']),
								'stanowisko_id'  => $this->Firewall->int($value['stanowsko_id']),
								'produkt_id'     => $this->Firewall->int($value['produkt_id']),
								'status'         => $this->Firewall->int($value['status']),
								'notatka'        => $this->Firewall->string($value['notatka']),
								'data_next_step' => $this->Firewall->date($value['data_next_step'])
				));
      }
    }
	}
	protected function _init() {
		$this->tDokumentyProduktu = $this->DB->tableDokumentyProduktu();
		$this->tDokumentyZadania = $this->DB->tableDokumentyZadania();
    $this->tZadania = $this->DB->tableZadania();
    $this->tZadaniaFirmy = $this->DB->tableZadaniaFirmy();
    $this->tZadaniaOpisy = $this->DB->tableZadaniaOpis();
		$DateTime = new \DateTime();
		$this->teraz = $DateTime->format('Y-m-d H:i:s');
		$DateTime->add(new \DateInterval('P1D'));
		$this->data_next_step = $DateTime->format('Y-m-d').' 00:00:00'; // Gdy data następnego kroku nie jest podana domyślnie należy wyznaczyć następny dzień
		$this->dataPrepared = & $this->arguments;
	}
	protected function _internalArguments(&$params) {
		$this->arguments[] = \Zadanie::create($params);
	}

	/**
	 * Chwila na którą obiekt ustawi czasy tworzenia zadania
	 * @var date-string
	 */
	protected $teraz;
	/**
	 * Czas kiedy krok zadania powinien być wykonany
	 * @var date-string
	 */
	protected $data_next_step;
	/**
	 * @var \DokumentyProduktuTable
	 */
	protected $tDokumentyProduktu;
	/**
   * @var \ZadaniaTable
   */
  protected $tZadania;
  /**
   * @var \ZadaniaOpisyTable
   */
  protected $tZadanieOpisy;
	/**
	 * @var \ZadaniaDokumentyTable
	 */
	protected $tDokumentyZadania;
}