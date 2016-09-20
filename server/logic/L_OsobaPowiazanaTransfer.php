<?php
/**
 * @package crmsw
 * @subpackage logic
 * @author Piotr Janczura <piotr@janczura.pl>
 * @task 2014-10-20 Przebudowa Table do obsługi zapytań preparowanych
 * @task 2014-10-20 Duża przebudowa DependencyTableRecord
 * @todo \pjpl\a\BusinessLogic <<< \crmsw\lib\a\BusinessLogic z usunięciem dziesdziczenia po klasie ...\beta\BusinessLogic
 */

class L_OsobaPowiazanaTransfer extends \crmsw\lib\a\BusinessLogic{
  public function __construct() {

    parent::__construct();
    $this->OsobyPowiazaneTable = $this->DB->tableOsobyPowiazane();
    $this->KlienciTable = $this->DB->tableKlienci();
  }

  protected function logic() {
    foreach ($this->dataIn as $key => $row) {
      try{
        $OsobaPowiazana = $this->OsobyPowiazaneTable->getRecord($row['osoba_powiazana_id'])->getEncja();
        $Klient = new Klient(
                $OsobaPowiazana->getNazwa(),
                $OsobaPowiazana->getImie(),
                $OsobaPowiazana->getPesel(),
                $OsobaPowiazana->getNip(),
                $OsobaPowiazana->getKodPoczt(),
                $OsobaPowiazana->getMiejscowosc(),
                $OsobaPowiazana->getUl(),
                $OsobaPowiazana->getNrB(),
                $OsobaPowiazana->getNrL(),
                $OsobaPowiazana->getEmail(),
                $OsobaPowiazana->getTelkom(),
                $OsobaPowiazana->getTeldom(),
                $OsobaPowiazana->getTelpraca(),
                $OsobaPowiazana->getOpis(),
                null, //  źródło
                $OsobaPowiazana->getFirmaId(),
                null, //  data_kont
                null, //  status
                date('Y-m-d'),
                null);
        $this->KlienciTable->createRecordImmediately($OsobaPowiazana);
        $this->OsobyPowiazaneTable->deleteIdImmediately($row['osoba_powiazana_id']);
        $this->dataOut[$key] = array('success'=>'true');
      } catch (\Exception $E) {
        $this->dataOut[$key] = array('success'=>'false');
        $this->catchLogicException($E);
      }
    }
  }

  public function fromRequest(&$_request) {
    if(isset($_request['data'])){
      foreach ($_request['data'] as $key => $value) {
        $row = array();
        if(isset($value['id'])){
          $row['osoba_powiazana_id'] = $this->Firewall->int($value['id']);
        }else{
          $row['osoba_powiazana_id'] = null;
        }
        $this->dataIn[$key] = $row;
      }
    }
  }
  /**
   * @var OsobyPowiazaneTable;
   */
  protected $OsobyPowiazaneTable;
  /**
   * @var KlienciTable
   */
  protected $KlienciTable;
}

