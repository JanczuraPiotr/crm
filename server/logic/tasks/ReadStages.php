<?php
namespace crmsw\logic\tasks;
use crmsw\lib\a\BusinessLogic;
use pjpl\db\Where
		as Where;
/**
 * @package crmsw
 * @subpackage logic
 * @author Piotr Janczura <piotr@janczura.pl>
 * @done 2014-09-22
 */
class ReadStages extends BusinessLogic{
  public function __construct() {
    parent::__construct();
		$this->tProdukty = $this->DB->tableProdukty();
		$this->tZadania = $this->DB->tableZadania();
		$this->tStanowiska = $this->DB->tableStanowiska();
		$this->tPracownicy = $this->DB->tablePracownicy();
		$this->tKlienci = $this->DB->tableKlienci();
		$this->tBanki = $this->DB->tableBanki();
		$this->tZatrudnienia = $this->DB->tableZatrudnienia();
  }
  protected function logic() {
		try{
			$Where = new Where($this->dataIn['filter']);
			$this->tZadania->where($Where)->limit($this->dataIn['start'], $this->dataIn['limit'])->load();

			$this->count = $this->tZadania->count() - 1 ; // Ostatni rekord zawiera jedynie datę następnego kroku i stanowisko_id które ma ten krok wykonać
 			for($rZadanie = $this->tZadania->getRecordFirst(); $rZadanie !== null; $rZadanie = $this->tZadania->getRecordNext()){
				$rKlient = $this->tKlienci->getRecord($rZadanie->klient_id);
				$rProdukt = $this->tProdukty->getRecord($rZadanie->produkt_id);
				$rBank = $this->tBanki->getRecord($rProdukt->getEncja()->getBankId());

				if( $rZadanie->stanowisko_id !== NULL ){
					if( $rZadanie->data_step !== NULL ){
						$rZatrudniony = $this->tZatrudnienia->getPracownikNaStanowikuWDniu($rZadanie->stanowisko_id, $rZadanie->data_step);
					}else{
						$rZatrudniony = $this->tZatrudnienia->getPracownikNaStanowikuWDniu($rZadanie->stanowisko_id, date('Y-m-d'));
						$rZadanie->notatka = '<center><b>Oczekiwanie na następną czynność z terminem : <br>'.$rZadanie->data_next_step.'</b></center>';
						// @todo Przenieść generowanie tego komunikatu do klienta
					}
					$rStanowisko = $this->tStanowiska->getRecord($rZadanie->stanowisko_id);
					$rPracownik = $this->tPracownicy->getRecord($rZatrudniony->getId());
				}else{
					$rStanowisko = NULL;
				}

				$row = array();
				$row['id']								= $rZadanie->id;
				$row['nr_zadania']				= $rZadanie->nr_zadania;
				$row['stanowisko_id']			=	$rZadanie->stanowisko_id;
				$row['data_next_step']		= $rZadanie->data_next_step;
				$row['produkt_id']				= $rProdukt->getId();
				$row['produkt_symbol']		= $rProdukt->getEncja()->getSymbol();
				$row['produkt_nazwa']			= $rProdukt->getEncja()->getNazwa();
				$row['bank_id']						= $rBank->getId();
				$row['bank_symbol']				= $rBank->getEncja()->getSymbol();
				$row['bank_nazwa']				= $rBank->getEncja()->getNazwa();
				$row['notatka']						= $rZadanie->notatka;
				$row['status_zadania_id']	= $rZadanie->status_zadania_id;
				if( $rStanowisko !== NULL ){
					$row['stanowisko_id']			= $rStanowisko->getId();
					$row['stanowisko_symbol']	=	$rStanowisko->getEncja()->getSymbol();
					$row['stanowisko_nazwa']	= $rStanowisko->getEncja()->getNazwa();
					if($rPracownik !== NULL){
						$row['pracownik_id']			= $rPracownik->getId();
						$row['pracownik_nazwa']		= $rPracownik->getEncja()->getNazwisko().' '.$rPracownik->getEncja()->getImie();
					}else{
						$row['pracownik_id']			= NULL;
						$row['pracownik_nazwa']		= NULL;
					}
				}else{
					$row['stanowisko_id']			= NULL;
					$row['stanowisko_symbol']	=	NULL;
					$row['stanowisko_nazwa']	= NULL;
					$row['pracownik_id']			= NULL;
					$row['pracownik_nazwa']		= NULL;
				}
				$row['data_step']						= $rZadanie->data_step;
				$this->dataOut[] = $row;
			}
		} catch (\Exception $ex) {
			$this->success = FALSE;
			$this->catchLogicException($ex);
		}
  }

  public function fromRequest(&$_request) {
		if(isset($_request['page'])){
			$this->dataIn['page'] = $this->Firewall->int($_request['page']);
		}  else {
			$this->dataIn['page'] = 0;
		}
		if(isset($_request['start'])){
			$this->dataIn['start'] = $this->Firewall->int($_request['start']);
		}  else {
			$this->dataIn['start'] = 0;
		}
		if(isset($_request['limit'])){
			$this->dataIn['limit'] = $this->Firewall->int($_request['limit']);
		}else{
			$this->dataIn['limit'] = 0;
		}
		if(isset($_request['filter'])){
			$this->dataIn['filter'] = $this->reformatExtJSFilter($_request['filter']);
		}else{
			$this->dataIn['filter'] = [];
		}
  }
	public function getJson(){
    return json_encode(array('success'=>  $this->success, 'totalCount'=> $this->tZadania->countFiltered(), 'count'=> $this->tZadania->count(), 'data'=>  $this->dataOut));
	}

	/**
	 * @var \StanowiskaTable
	 */
	protected $tStanowiska;
	/**
	 * @var \PracownicyTable
	 */
	protected $tPracownicy;
	/**
	 * @var \ZadaniaTable
	 */
	protected $tZadania;
	/**
	 * @var \ProduktyTable
	 */
	protected $tProdukty;
	/**
	 * @var \BankiTable
	 */
	protected $tBanki;
	/**
	 * @var \KlienciTable
	 */
	protected $tKlienci;
	/**
	 * @var \ZatrudnieniaTable
	 */
	protected $tZatrudnienia;
}