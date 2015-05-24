<?php
use crmsw\lib\a\BusinessLogic;
use pjpl\db\Where;
/**
 * @package crmsw
 * @subpackage database
 * @author Piotr Janczura <piotr@janczura.pl>
 * @confirm 2014-11-04
 * @confirm 2014-11-05 Przebudowa Table do obsługi zapytań preparowanych
 * @confirm 2014-11-05 Duża przebudowa DependencyTableRecord
 * @todo \pjpl\a\BusinessLogic <<< \crmsw\lib\a\BusinessLogic z usunięciem dziesdziczenia po klasie ...\beta\BusinessLogic
 */
class L_DokumentyZadaniaRead extends \crmsw\lib\a\BusinessLogic{
	public function __construct() {
		parent::__construct();
		$this->tDokumentyZadania = $this->DB->tableDokumentyZadania();
		$this->tDokumentySlownik = $this->DB->tableDokumentySlownik();
	}
	protected function logic() {
		try{
			$Where = new Where($this->dataIn['filter']);
			$this->tDokumentyZadania->where($Where)->load();
			$this->tDokumentySlownik->load();

			for($rDokumentZadania = $this->tDokumentyZadania->getRecordFirst(); $rDokumentZadania !== NULL; $rDokumentZadania = $this->tDokumentyZadania->getRecordNext()){
				$rSlownik = $this->tDokumentySlownik->getRecord($rDokumentZadania->slownik_id);
				$row = array();
				$row['id']                = $rDokumentZadania->id;
				$row['slownik_id']        = $rSlownik->id;
				$row['slownik_symbol']    = $rSlownik->symbol;
				$row['slownik_nazwa']     = $rSlownik->nazwa;
				$row['nr_zadania']        = $rDokumentZadania->nr_zadania;
				$row['adnotacje']         = $rDokumentZadania->adnotacje;
				$row['data_dostarczenia'] = $rDokumentZadania->data_dostarczenia;
				$this->dataOut[] = $row;
				$this->countTodalOut = $this->countFilteredOut = $this->tDokumentyZadania->countFiltered();
				$this->countOut = $this->tDokumentyZadania->count();
			}
		} catch (\Exception $E) {
			$this->catchLogicException($E);
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
	 * @var ZadaniaDokumentyTable
	 */
	protected $tDokumentyZadania;
	/**
	 * @var DokumentySlownikTable
	 */
	protected $tDokumentySlownik;
}