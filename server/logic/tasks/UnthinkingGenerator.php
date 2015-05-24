<?php
namespace crmsw\logic\tasks;
use crmsw\lib\a\beta\BusinessLogic;
use pjpl\db\Record;
use pjpl\db\Where;
/**
 * Generator zadań przydzielający wskazany produkt wszystkim klientom, którzy tego produktu nie mieli oferowanego.
 * Podczas przydzielenia zadania nie ma możliwości określenia żadnych warunków przydziału zadań i dla tego nazywa się "bezmyślnym".
 *
 * W obecnej formie generowane zadania nie są generowane całkiem bezmyślnie ponieważ na wejściu zadawany jest produkt dla którego
 * tworzone są zadania. Należało by zrobić całkem bezmyśłny generator który wygeneruje zadania na podstawie wszystkich produktów wszystkim klientom
 *
 * @package crmsw
 * @subpackage logic
 * @author Piotr Janczura <piotr@janczura.pl>
 * @confirm 2014-12-20
 * @doc 2014-12-20
 */
class UnthinkingGenerator extends BusinessLogic{
	public function __construct() {
		parent::__construct();
		$DateTime = new \DateTime();
		$this->teraz = $DateTime->format('Y-m-d H:i:s');
		$DateTime->add(new \DateInterval('P1D'));
		$this->data_next_step = $DateTime->format('Y-m-d').' 00:00:00'; // Gdy data następnego kroku nie jest podana domyślnie należy wyznaczyć następny dzień

		$this->arguments['produkt_id'] = NULL;
		$this->arguments['firma_id'] = NULL;
		$this->arguments['data_next_step'] = $this->data_next_step;
	}
	protected function _action() {
		try{
			$this->DB->beginTransaction();
			for($rKlient = $this->tKlienciFirmy->getRecordFirst(); $rKlient !== NULL; $rKlient = $this->tKlienciFirmy->getRecordNext()){

				$params = [
						'klient_id'         => $rKlient->getId(),
						'produkt_id'        => $this->dataPrepared['produkt_id'],
						'stanowisko_id'     => $this->stanowisko_id,
						'status_zadania_id' => 0,
						'notatka'           => $this->sposob_utworzenia,
						'data_next_step'    =>  $this->dataPrepared['data_next_step']
				];
				$this->_algorithm($params);

			}
			$this->dataOut['count'] = $this->count;
			$this->DB->commit();
		}catch(\Exception $E){
			$this->DB->rollBack();
			$this->success = FALSE;
		}
  }
	protected function _init() {
		$success = true;

		$this->tProdukty = $this->DB->tableProdukty();
		$this->tBankiOddzialy = $this->DB->tableBankiOddzialy();
		$this->tFirmyOddzialy = $this->DB->tableFirmyOddzialy();
		$this->tBankiOddzialyFirmyOddzialy = $this->DB->tableBankiOddzialyFirmyOddzialy();
    $this->tZadania = $this->DB->tableZadania();
    $this->tZadaniaFirmy = $this->DB->tableZadaniaFirmy();

		// @confirm 2014-09-02
		// Atomatycznie przypisuje generowane zadania tylko do stanowiska pracownika gdy zadanie było generowane przez pracownika.
		if(isset($_SESSION['USER_STATUS'])){
			switch ((int)$_SESSION['USER_STATUS']){
				case \CRM::PRACOWNIK_KIEROWNIK:
				case \CRM::PRACOWNIK_LIDER:
				case \CRM::PRACOWNIK_ZWYKLY:
					if(isset($_SESSION['STANOWISKO_ID']) && $_SESSION['STANOWISKO_ID'] !== NULL){
						$this->stanowisko_id = $_SESSION['STANOWISKO_ID'];
					}else{
						$this->stanowisko_id = null;
					}
				break;
				default:
					$this->stanowisko_id = null;
			}
		}
		// @confirm 2014-09-02t0
		// Komunikat o sposobie utworzenia zadania.
		if(isset($_SESSION['USER_STATUS'])){
			switch ((int)$_SESSION['USER_STATUS']){
				case \CRM::PRACOWNIK_LIDER:
					$this->sposob_utworzenia = 'zadanie utworzone przez lidera grupy';
					break;
				case \CRM::PRACOWNIK_ZWYKLY:
					$this->sposob_utworzenia = 'zadanie utworzone samodzielnie';
					break;
				default :
					$this->sposob_utworzenia = 'zadanie wygenerowane zbiorczo';
			}
		}

		$this->rProdukt = $this->tProdukty->getRecord($this->arguments['produkt_id']);
		$this->bank_id = $this->rProdukt->getEncja()->getBankId();
		$this->tFirmyOddzialy->where(new Where([
				[
						'attribute' => 'firma_id',
						'operator'  => '=',
						'value'     => $this->arguments['firma_id']
				],[
						'attribute' => 'data_do',
						'operator'   => '=',
						'value'     => NULL
				]
		]))->load();
		$this->firma_oddzial_ids = $this->tFirmyOddzialy->getIds();
		$this->tBankiOddzialy->where(new Where([
				[
						'attribute' => 'bank_id',
						'operator'  => '=',
						'value'     => $this->bank_id
				],[
						'attribute' => 'data_do',
						'operator'   => '=',
						'value'     => NULL
				]
		]))->load();
		$this->bank_oddzial_ids = $this->tBankiOddzialy->getIds();
		$this->tBankiOddzialyFirmyOddzialy->where(new Where([
				[
						'attribute' => 'data_do',
						'operator'   => '=',
						'value'     => NULL
				]
		]))->load();
		$this->aJoin = [];
		for($rJoin = $this->tBankiOddzialyFirmyOddzialy->getRecordFirst(); $rJoin !== NULL; $rJoin = $this->tBankiOddzialyFirmyOddzialy->getRecordNext() ){
			if( ( array_search($rJoin->getEncja()->getFirmaOddzialId(), $this->firma_oddzial_ids) !== NULL) && (array_search($rJoin->getEncja()->getBankOddzialId(), $this->bank_oddzial_ids) !== NULL) ){
				$this->aJoin[] = $rJoin;
			}
		}
		if(count($this->aJoin) === 0){
			$success = FALSE;
		}else{
			// Tak, w firmie firma_id może być sprzedawany produkt banku bank_id.
			// Należy zbudować listę klientów, którym można zaproponować produkt są nimi klienci, których firma_id zgadza się z tą firmą a data_do == null.
			$this->tKlienciFirmy = $this->DB->tableKlienci();
			$this->tKlienciFirmy->where(new Where([
					[
							'attribute' => 'firma_id',
							'operator'  => '=',
							'value'     => $this->arguments['firma_id']
					],[
							'attribute' => 'data_do',
							'operator'   => '=',
							'value'     => NULL
					]
			]))->load();
		}


		$this->dataPrepared = &$this->arguments;
		$this->success = $success;
	}
	protected function _algorithm(& $params) {
		$this->tZadania->where(new Where([
				[
						'attribute' => 'klient_id',
						'operator'  => '=',
						'value'     => $params['klient_id']
				],[
						'attribute' => 'produkt_id',
						'operator'  => '=',
						'value'     => $params['produkt_id']
				]
		]))->load();
		if($this->tZadania->count() > 0 ){
			// Klient miał już proponowany ten produkt.
			return ;
		}
		$Zadanie = \Zadanie::create([
				'klient_id'         => $params['klient_id'],
				'produkt_id'        => $params['produkt_id'],
				'stanowisko_id'     => $params['stanowisko_id'],
				'status_zadania_id' => $params['status_zadania_id'],
				'notatka'           => $params['notatka'],
				'data_next_step'    => $params['data_next_step']
		]);
		$Create = new Create();
		$Create->internalCall($Zadanie->toArray());
		$this->count++ ;
	}
	protected function _externalArguments(&$set_params) {
		if(isset($set_params['produkt_id'])){
			$this->arguments['produkt_id'] = $this->Firewall->int($set_params['produkt_id']);
		}
		if(isset($set_params['firma_id'])){
			$this->arguments['firma_id'] = $this->Firewall->int($set_params['firma_id']);
		}
		if(isset($set_params['data_next_step'])){
			$this->arguments['data_next_step'] = $this->Firewall->date($set_params['data_next_step']);
		}
	}
	protected function _internalArguments(&$set_params) {
		foreach ($set_params as $key => $params) {
			$this->arguments[$key] = $params;
		}
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
	 * Ilość utworzonych zadan
	 * @var int
	 */
	protected $count = 0;
	/**
	 * Tablica połączeń oddziałów firm i oddziałów banków wygenerowana dla tych banków, które mają w ofercie produkt przeznaczony do sprzedaży klientowi.
	 * @var array
	 */
	protected $aJoin;
	/**
	 * Identyfikator banku sprzedającego produkt $rProdukt
	 * @var int
	 */
	protected $bank_id;
	/**
	 * Identyfikatory oddziałów firm w których może być sprzedawany $rProdukt
	 * @var array
	 */
	protected $firma_oddzil_ids;
	/**
	 * Odentyfikatory oddziałów banków za pośrednictwem którego bank może sprzedawać produkt
	 * @var array
	 */
	protected $bank_oddzial_ids;
	/**
	 * Jeżeli generowanie zadań odbywa się ze zwykłego stanowiska pracy to jest to generowanie zadań samemu sobie i id razu
	 * zadanie zostanie przypisane to $stanowisko_id;
	 * @var int
	 */
	protected $stanowisko_id;
	/**
	 * Informacja wsawiana jako treść notatki do nowoutworzonego zadania
	 * @var string
	 */
	protected $sposob_utworzenia;
	/**
	 * Produkt dla którego tworzone jest zadanie
	 * @var Record;
	 */
	protected $rProdukt;
	/**
	 * @var \BankiOddzialyFirmyOddzialyTable
	 */
	protected $tBankiOddzialyFirmyOddzialy;
	/**
	 * @var \BankiOddzialyTable
	 */
	protected $tBankiOddzialy;
	/**
	 * @var \FirmyOddzialyTable
	 */
	protected $tFirmyOddzialy;
	/**
	 * @var \KlienciTable
	 */
	protected $tKlienciFirmy;
	/**
	 * @var \ProduktyTable
	 */
	protected $tProdukty;
	/**
	 * @var \ZadaniaTable
	 */
	protected $tZadania;
	/**
	 * @var \ZadaniaFirmyTable
	 */
	protected $tZadaniaFirmy;
}