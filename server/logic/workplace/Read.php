<?php
namespace crmsw\logic\workplace;
use crmsw\lib\a\BusinessLogic;
use pjpl\db\Where;

/**
 * @package crmsw
 * @subpackage database
 * @author Piotr Janczura <piotr@janczura.pl>
 * @confirm 2014-12-16
 */
class Read extends BusinessLogic{
	public function __construct() {
		parent::__construct();
		$this->tStanowiska = $this->DB->tableStanowiska();
		$this->tPracownicy = $this->DB->tablePracownicy();
	}
	protected function logic() {
		try{
			$Where = new Where($this->dataIn['filter']);
			$this->tStanowiska->where($Where)->limit($this->dataIn['start'], $this->dataIn['limit'])->load();

			for( $rStanowisko = $this->tStanowiska->getRecordFirst(); $rStanowisko !== NULL; $rStanowisko = $this->tStanowiska->getRecordNext() ){
				$pracownik_id = $rStanowisko->getEncja()->getPracownikId();
				$pracownik = array();
				if($pracownik_id !== null){
					$rPracownik = $this->tPracownicy->getRecord($pracownik_id);
					$pracownik['pracownik'] = $rPracownik->getEncja()->getNazwisko().' '.$rPracownik->getEncja()->getImie();
					$pracownik['pesel'] = $rPracownik->getEncja()->getPesel();
				}else{
					$pracownik['pracownik'] = 'Wakat';
					$pracownik['pesel'] = '';
				}
				$this->dataOut[] = array_merge($rStanowisko->toArray() , $pracownik);
			}
			$this->countOut = $this->tStanowiska->count();
			$this->countTodalOut = $this->tStanowiska->countTotal();
			$this->countFilteredOut = $this->tStanowiska->countFiltered();
		} catch (\Exception $ex) {
			$this->success = FALSE;
			$this->catchException($ex);
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
	 * @var \StanowiskaTable
	 */
	protected $tStanowiska;
	/**
	 * @var \PracownicyTable
	 */
	protected $tPracownicy;
}