<?php
namespace crmsw\logic\tasks;
use crmsw\lib\a\BusinessLogic as BusinessLogic;
use pjpl\db\Where as Where;
/**
 * @package crmsw
 * @subpackage logic
 * @author Piotr Janczura <piotr@janczura.pl>
 * @done 2014-12-16
 */
class ReadHeadlines extends BusinessLogic{
  public function __construct() {
    parent::__construct();
		$this->tBanki = $this->DB->tableBanki();
		$this->tKlienci = $this->DB->tableKlienci();
		$this->tProdukty = $this->DB->tableProdukty();
		$this->tZadania = $this->DB->tableZadania();

  }
  protected function logic() {
		try{
			$Where = new Where($this->dataIn['filter']);
			$Where->append('data_step', '=', NULL);
			$this->tZadania->where($Where)->limit($this->dataIn['start'], $this->dataIn['limit'])->load();
			for($rZadanie = $this->tZadania->getRecordFirst(); $rZadanie !== null; $rZadanie = $this->tZadania->getRecordNext()){
				$rKlient = $this->tKlienci->getRecord($rZadanie->klient_id);
				$rProdukt = $this->tProdukty->getRecord($rZadanie->produkt_id);
				$rBank = $this->tBanki->getRecord($rProdukt->bank_id);
				$row = array();
				$row['id']								= $rZadanie->id;
				$row['nr_zadania']				= $rZadanie->nr_zadania;
				$row['stanowisko_id']			=	$rZadanie->stanowisko_id;
				$row['klient_id']					= $rKlient->id;
				$row['klient_nazwa']			= $rKlient->nazwa.' '.$rKlient->imie;
				$row['klient_nazwisko']		= $rKlient->nazwa;
				$row['klient_imie']				= $rKlient->imie;
				$row['unique_typ']				= ($rKlient->nip !== NULL ? 'nip' : ($rKlient->pesel !== NULL ? 'pesel' : NULL) );
				$row['unique_value']			= ($rKlient->nip !== NULL ? $rKlient->nip : ($rKlient->pesel !== NULL ? $rKlient->pesel : NULL) );
				$row['kod_poczt']					= $rKlient->kod_poczt;
				$row['miejscowosc']				= $rKlient->miejscowosc;
				$row['ul']								= $rKlient->ul;
				$row['nr_b']							= $rKlient->nr_b;
				$row['nr_l']							= $rKlient->nr_l;
				$row['telkom']						= $rKlient->telkom;
				$row['teldom']						= $rKlient->teldom;
				$row['telpraca']					= $rKlient->telpraca;
				$row['email']							= $rKlient->email;
				$row['produkt_id']				= $rProdukt->id;
				$row['produkt_symbol']		= $rProdukt->symbol;
				$row['produkt_nazwa']			= $rProdukt->nazwa;
				$row['produkt_opis']			= $rProdukt->opis;
				$row['bank_id']						= $rBank->id;
				$row['bank_symbol']				= $rBank->symbol;
				$row['bank_nazwa']				= $rBank->symbol;
				$row['data_next_step']		= $rZadanie->data_next_step;
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

	/**
	 * @var \ZadaniaTable
	 */
	protected $tZadania;
	/**
	 * @var \KlienciTable
	 */
	protected $tKlienci;
	/**
	 * @var \ProduktyTable
	 */
	protected $tProdukty;
	/**
	 * @var \BankiTable
	 */
	protected $tBanki;
}