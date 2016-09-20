<?php
namespace crmsw\logic\tasks;
use crmsw\lib\a\BusinessLogic
		as BusinessLogic;
use pjpl\db\Where
		as Where;
/**
 * Wczytuje wszystkie adnotacje dodane do zadania. W interfejsie urzytkownika adnotacje te nazywane są też "aktami sprawy".
 * @package crmsw
 * @subpackage logic
 * @author Piotr Janczura <piotr@janczura.pl>
 * @done 2014-12-16
 */
class ReadDescription extends BusinessLogic{
 public function __construct() {
    parent::__construct();
    $this->tZadaniaOpis = $this->DB->tableZadaniaOpis();
  }
	protected function logic() {
		try{
			$Where = new Where($this->dataIn['filter']);
			$this->tZadaniaOpis->where($Where)->limit($this->dataIn['start'], $this->dataIn['limit'])->load();
			for($rZadanieOpis = $this->tZadaniaOpis->getRecordFirst(); $rZadanieOpis !== NULL; $rZadanieOpis = $this->tZadaniaOpis->getRecordNext()){
				$this->dataOut[] = array_merge($rZadanieOpis->toArray(),array('create'=>$rZadanieOpis->getCreate()));
			}
		} catch (\Exception $E) {
			$this->success = FALSE;
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
		}  else {
			$this->dataIn['filter'] = [];
		}
	}
	/**
   * @var \ZadaniaOpisTable
   */
  protected $tZadaniaOpis;
}