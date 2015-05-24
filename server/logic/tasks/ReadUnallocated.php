<?php
namespace crmsw\logic\tasks;
use crmsw\lib\a\BusinessLogic;
use pjpl\db\Where;
/**
 * Klasa ma odczytać zadania nie przydzielone
 * @package crmsw
 * @subpackage logic
 * @author Piotr Janczura <piotr@janczura.pl>
 * @confirm 2014-12-20
 */
class ReadUnallocated extends BusinessLogic{
  public function __construct() {
    parent::__construct();
		$this->tZadania = $this->DB->tableZadania();
		$this->tBanki = $this->DB->tableBanki();
		$this->tKlienci = $this->DB->tableKlienci();
		$this->tProdukty = $this->DB->tableProdukty();
  }

	protected function logic() {
		try{
			$this->tZadania->where(new Where([
					[
							'attribute' => 'stanowisko_id',
							'operator'  => '=',
							'value'     => NULL
					],[
							'attribute' => 'data_step',
							'operator'  => '=',
							'value'     => NULL
					]
			]))->load();

			for($rZadanie = $this->tZadania->getRecordFirst(); $rZadanie !== null; $rZadanie = $this->tZadania->getRecordNext()){
				$rKlient = $this->tKlienci->getRecord($rZadanie->klient_id);
				$rProdukt = $this->tProdukty->getRecord($rZadanie->produkt_id);
				$rBank = $this->tBanki->getRecord($rProdukt->getEncja()->getBankId());
				$row = array();
				$row['id']						 = $rZadanie->id;
				$row['nr_zadania']		 = $rZadanie->nr_zadania;
				$row['data_next_step'] = $rZadanie->data_next_step;
				$row['klient_id']			 = $rKlient->getId();
				$row['klient_nazwa']	 = $rKlient->nazwa.' '.$rKlient->imie;
				$row['unique_typ']		 = ($rKlient->nip !== NULL ? 'nip' : ($rKlient->pesel !== NULL ? 'pesel' : NULL) );
				$row['unique_value']	 = ($rKlient->nip !== NULL ? $rKlient->nip : ($rKlient->nip !== NULL ? $rKlient->nip : NULL) );
				$row['kod_poczt']			 = $rKlient->kod_poczt;
				$row['miejscowosc']		 = $rKlient->miejscowosc;
				$row['ul']						 = $rKlient->ul;
				$row['nr_b']					 = $rKlient->nr_b;
				$row['nr_l']					 = $rKlient->nr_l;
				$row['telkom']				 = $rKlient->telkom;
				$row['teldom']				 = $rKlient->teldom;
				$row['telpraca']			 = $rKlient->telpraca;
				$row['email']					 = $rKlient->email;
				// @todo informacja o poukcie i banku została przniesiona do informacji o kroku
				$row['produkt_id']		 = $rProdukt->getId();
				$row['produkt_symbol'] = $rProdukt->symbol;
				$row['produkt_nazwa']	 = $rProdukt->nazwa;
				$row['bank_id']				 = $rBank->getId();
				$row['bank_symbol']		 = $rBank->symbol;
				$row['bank_nazwa']		 = $rBank->nazwa;
				$this->dataOut[] = $row;
				$this->countTodalOut = $this->tZadania->countTotal();
				$this->countFilteredOut = $this->tZadania->countFiltered();
				$this->countOut = $this->tZadania->count();
			}
		} catch (Exception $ex) {
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
