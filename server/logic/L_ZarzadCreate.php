<?php
/**
 * @package crmsw
 * @subpackage logic
 * @author Piotr Janczura <piotr@janczura.pl>
 * @work 2014-10-20 Przebudowa Table do obsługi zapytań preparowanych
 * @work 2014-10-20 Duża przebudowa DependencyTableRecord
 * @todo \pjpl\a\BusinessLogic <<< \crmsw\lib\a\BusinessLogic z usunięciem dziesdziczenia po klasie ...\beta\BusinessLogic
 */
class L_ZarzadCreate extends \crmsw\lib\a\BusinessLogic{
  public function __construct() {
    parent::__construct();
    $this->ZarzadyTable = $this->DB->tableZarzady();
  }

  protected function logic() {
    foreach ($this->dataIn as $key => $row) {
      try{
        if(
             (
                isset($row['haslo']) && isset($row['hasloPowt'])
             )
                &&
             (
                $row['haslo'] === $row['hasloPowt']
             )
           ){
        }else{
          $E = new \pjpl\depreciate\EBadIn(__CLASS__, __METHOD__, '$haslo', '');
        }
        $Zarzad = new Zarzad($row['zarzadca_id'], $row['firma_id'], $row['login'], $row['haslo'], $row['data_od']);
        $this->dataOut[$key] = array('success'=>'true','id'=>  $this->ZarzadyTable->createRecordsArrayImmediately($Zarzad)->getId());
      }  catch (Exception $E){
        $this->dataOut[$key] = array('success'=>'false');
        $this->catchLogicException($E);
      }
    }
  }

  public function fromRequest(&$_request) {
    if(isset($_request['data'])){
      foreach ($_request['data'] as $key => $value) {
        $row = array();
        if(isset($value['zarzadca_id'])){
          $row['zarzadca_id'] = $this->Firewall->int($value['zarzadca_id']);
        }  else {
          $row['zarzadca_id'] = 0;
        }
        if(isset($value['firma_id'])){
          $row['firma_id'] = $this->Firewall->int($value['firma_id']);
        }  else {
          $row['firma_id'] = 0;
        }
        if(isset($value['login'])){
          $row['login'] = $this->Firewall->login($value['login']);
        }  else {
          $row['login'] = '';
        }
        if(isset($value['haslo'])){
          $row['haslo'] = $this->Firewall->password($value['haslo']);
        }  else {
          $row['haslo'] = '';
        }
        if(isset($value['hasloPowt'])){
          $row['hasloPowt'] = $this->Firewall->password($value['hasloPowt']);
        }  else {
          $row['hasloPowt'] = '';
        }
        if(isset($value['data_od'])){
          $row['data_od'] = $this->Firewall->date($row['data_od']);
        }  else {
          $row['data_od'] = date('Y-m-d');
        }
        $this->dataIn[$key] = $row;
      }
    }
  }
  /**
   * @var PracownicyTable
   */
  protected $PracownicyTable;
}