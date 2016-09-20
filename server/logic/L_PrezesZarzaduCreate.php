<?php
/**
 * @package crmsw
 * @subpackage logic
 * @author Piotr Janczura <piotr@janczura.pl>
 * @done 2014-12-30
 * @todo \pjpl\a\BusinessLogic <<< \crmsw\lib\a\BusinessLogic z usunięciem dziesdziczenia po klasie ...\beta\BusinessLogic
 */
class L_PrezesZarzaduCreate extends \crmsw\lib\a\BusinessLogic{
  public function __construct() {
    parent::__construct();
    $this->ZarzadyTable = $this->DB->tableZarzady();
  }
  protected function logic() {
		try{

			$this->DB->beginTransaction();
			$Where = new pjpl\db\Where([
					[
							'attribute' => 'firma_id',
							'operator'  => '=',
							'value'     => $this->dataIn['firma_id']
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
			if($this->ZarzadyTable->count() > 0){
				// Firma ma już prezesa. Jeżeli jest nią ta sama osoba kończ pracę. Jeżeli jest nią ktoś inny zaznacz że z podaną datą przestał nim być
				$rPrezes = $this->ZarzadyTable->getRecordFirst();
				if($rPrezes->getEncja()->getPracownikId() !== $this->dataIn['pracownik_id']){
					// Zmiana prezesa
					$rPrezes->getEncja()->setDataDo($this->dataIn['data_od']);
					$rPrezes->updateImmediately();
					$this->create($this->dataIn);
				}
			}else{
				$this->create($this->dataIn);
			}
			$this->DB->commit();
		}  catch (\Exception $E){
			$this->DB->rollBack();
			$this->success = FALSE;
			$this->catchLogicException($E);
		}
  }
	private function create($in){
		$Prezes = \Zarzad::create([
				'status'       => CRM::ZARZAD_PREZES,
				'pracownik_id' => $in['pracownik_id'],
				'firma_id'     => $in['firma_id'],
				'data_od'      => $in['data_od'],
				'data_do'      => null
		]);
		$this->dataOut[] = array('id'=>$this->ZarzadyTable->createRecordImmediately($Prezes)->getId(),'tmpId'=>  $this->dataIn['tmpId']);
	}
  public function fromRequest(&$_request) {
		if(isset($_request['tmpId'])){
			$this->dataIn['tmpId'] = $this->Firewall->login($_request['tmpId']);
		}  else {
			$this->dataIn['tmpId'] = null;
		}
		if(isset($_request['pracownik_id'])){
			$this->dataIn['pracownik_id'] = $this->Firewall->int($_request['pracownik_id']);
		}  else {
			$this->dataIn['pracownik_id'] = null;
		}
		if(isset($_request['firma_id'])){
			$this->dataIn['firma_id'] = $this->Firewall->int($_request['firma_id']);
		}  else {
			$this->dataIn['firma_id'] = null;
		}
		if(isset($_request['data_od'])){
			$this->dataIn['data_od'] = $this->Firewall->date($_request['data_od']);
		}  else {
			$this->dataIn['data_od'] = date('Y-m-d');
		}
  }
	/**
	 * @var \ZarzadyTable
	 */
	protected $ZarzadyTable;
}