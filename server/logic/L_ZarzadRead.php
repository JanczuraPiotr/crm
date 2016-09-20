<?php
/**
 * @package crmsw
 * @subpackage logic
 * @author Piotr Janczura <piotr@janczura.pl>
 * @done 2014-12-30
 * @todo \pjpl\a\BusinessLogic <<< \crmsw\lib\a\BusinessLogic z usunięciem dziesdziczenia po klasie ...\beta\BusinessLogic
 */
class L_ZarzadRead extends \crmsw\lib\a\BusinessLogic{
  public function __construct() {
    parent::__construct();
    $this->ZarzadyTable = $this->DB->tableZarzady();
    $this->PracownicyTable = $this->DB->tablePracownicy();
  }
  protected function logic() {
		try{
			$this->PracownicyTable->limit($this->dataIn['start'], $this->dataIn['limit'])->load();

			for($rPracownik = $this->PracownicyTable->getRecordFirst(); $rPracownik !== NULL; $rPracownik = $this->PracownicyTable->getRecordNext() ){
				$Where = new pjpl\db\Where([
						[
								'attribute' => 'pracownik_id',
								'operator'  => '=',
								'value'     => $rPracownik->getId()
						],[
								'attribute' => 'firma_id',
								'operator'  => '=',
								'value'     => $rPracownik->firma_id
						],[
								'attribute' => 'status',
								'operator'  => '=',
								'value'     => CRM::ZARZAD_PREZES
						],[
								'attribute' => 'data_do',
								'operator'  => '=',
								'value'     => NULL
						]
				]);

				$this->ZarzadyTable->where($Where)->load();
				if( $this->ZarzadyTable->count() > 0 ){
					$this->dataOut[] = array_merge($rPracownik->toArray(), ['prezes' => TRUE]);
				} else {
					$this->dataOut[] = array_merge($rPracownik->toArray(), ['prezes' => FALSE]);
				}
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
		if(isset($_request['filter'])){
			$this->dataIn['filter'] = $this->reformatExtJSFilter($_request['filter']);
		}  else {
			$this->dataIn['filter'] = [];
		}
  }

  /**
   * @var \ZarzadyTable
   */
  protected $ZarzadyTable;
  /**
   * @var \PracownicyTable
   */
  protected $PracownicyTable;
}