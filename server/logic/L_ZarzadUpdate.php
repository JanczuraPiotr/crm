<?php
/**
 * @package crmsw
 * @subpackage logic
 * @author Piotr Janczura <piotr@janczura.pl>
 * @task 2014-10-20 Przebudowa Table do obsługi zapytań preparowanych
 * @task 2014-10-20 Duża przebudowa DependencyTableRecord
 * @todo \pjpl\a\BusinessLogic <<< \crmsw\lib\a\BusinessLogic z usunięciem dziesdziczenia po klasie ...\beta\BusinessLogic
 */
class L_ZarzadUpdate extends \crmsw\lib\a\BusinessLogic{
  public function __construct() {
    parent::__construct();
    $this->ZarzadyTable = $this->DB->tableZarzady();
  }

  protected function logic() {
    foreach ($this->dataIn as $key => $row) {
      $Record = $this->ZarzadyTable->getRecord($row['id']);
      $Zarzad = $Record->getEncja();
      $Zarzad->setZarzadca($row['zarzadca_id']);
      $Zarzad->setFirmaId($row['firma_id']);
      // Do zmiany danych logowania służy klasa LZarzadUpdateLoginPass
      //$Zarzad->setLogin($row['login']);
      //$Zarzad->setHaslo($row['haslo']);
      $Zarzad->setDataOd($row['data_od']);
      $Zarzad->setDataDo($row['data_do']);
      try{
        $Record->updateImmediately();
        $this->dataOut[$key] = array('success'=>'true');
      }catch (Exception $E){
        $this->success = FALSE;
        $this->catchLogicException($E);
      }
    }
  }

  public function fromRequest(&$_request) {
    if(isset($_request['data'])){
      foreach ($_request['data'] as $key => $value) {
        $row = array();
        if(isset($value['id'])){
          $row['id'] = $this->Firewall->word($value['id']);
        }else{
          $row['id'] = '';
        }
        if(isset($value['pracownik_id'])){
          $row['pracownik_id'] = $this->Firewall->int($value['pracownik_id']);
        }else{
          $row['pracownik_id'] = 0;
        }
        if(isset($value['firma_id'])){
          $row['firma_id'] = $this->Firewall->int($value['firma_id']);
        }else{
          $row['firma_id'] = 0;
        }
        if(isset($value['login'])){
          $row['login'] = $this->Firewall->login($value['login']);
        }else{
          $row['login'] = '';
        }
        if(isset($value['haslo'])){
          $row['haslo'] = $this->Firewall->password($value['haslo']);
        }else{
          $row['haslo'] = '';
        }
        if(isset($value['data_od'])){
          $row['data_od'] = $this->Firewall->date($value['data_od']);
        }else{
          $row['data_od'] = '';
        }
        if(isset($value['data_do'])){
          $row['data_do'] = $this->Firewall->date($value['data_do']);
        }  else {
          $row['data_do'] = '';
        }
        $this->dataIn[$key] = $row;
      }
    }
  }
  /**
   * @var ZarzadyTable
   */
  protected $ZarzadyTable;

}