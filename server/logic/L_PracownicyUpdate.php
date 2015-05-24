<?php
/**
 * @package crmsw
 * @subpackage logic
 * @author Piotr Janczura <piotr@janczura.pl>
 * @prace 2014-10-20 Przebudowa Table do obsługi zapytań preparowanych
 * @prace 2014-10-20 Duża przebudowa DependencyTableRecord
 * @todo \pjpl\a\BusinessLogic <<< \crmsw\lib\a\BusinessLogic z usunięciem dziesdziczenia po klasie ...\beta\BusinessLogic
 */
class L_PracownicyUpdate extends \crmsw\lib\a\BusinessLogic{
  public function __construct() {
    parent::__construct();
    $this->PracownicyTable = $this->DB->tablePracownicy();
  }

  protected function logic() {
    foreach ($this->dataIn as $key => $row) {
			try{
				$Record = $this->PracownicyTable->getRecord($row['id']);
				$Pracownik = $Record->getEncja();
				if( $row['firma_id'] )    { $Pracownik->firma_id    = $row['firma_id'];    }
				if( $row['nazwisko'] )    { $Pracownik->nazwisko    = $row['nazwisko'];    }
				if( $row['imie'] )        { $Pracownik->imie        = $row['imie'];        }
				if( $row['pesel'] )       { $Pracownik->pesel       = $row['pesel'];       }
				if( $row['kod_poczt'] )   { $Pracownik->kod_poczt   = $row['kod_poczt'];   }
				if( $row['miejscowosc'] ) { $Pracownik->miejscowosc = $row['miejscowosc']; }
				if( $row['ul'] )          { $Pracownik->ul          = $row['ul'];          }
				if( $row['nr_b'] )        { $Pracownik->nr_b        = $row['nr_b'];        }
				if( $row['nr_l'] )        { $Pracownik->nr_l        = $row['nr_l'];        }
				if( $row['tel'] )         { $Pracownik->tel         = $row['tel'];         }
				if( $row['email'] )       { $Pracownik->email       = $row['email'];       }
				if( $row['data_od'] )     { $Pracownik->data_od     = $row['data_od'];     }
				if( $row['data_do'] )     { $Pracownik->data_do     = $row['data_do'];     }
				$Record->updateImmediately();
        $this->dataOut[$key] = array('success'=>true,'id'=>$row['id']);
      }catch (\Exception $E){
        $this->success = FALSE;
        $this->catchLogicException($E);
      }
    }
  }

  public function fromRequest(&$_request) {
    $we = json_decode($_request, TRUE);
		foreach ($we['data'] as $key => $value) {
			$row = array();
			if(isset($value['id'])){
				$row['id'] = $this->Firewall->int($value['id']);
			}else{
				$row['id'] = null;
			}
			if(isset($value['firma_id'])){
				$row['firma_id'] = $this->Firewall->int($value['firma_id']);
			}else{
				$row['firma_id'] = null;
			}
			if(isset($value['nazwisko'])){
				$row['nazwisko'] = $this->Firewall->string($value['nazwisko']);
			}else{
				$row['nazwisko'] = NULL;
			}
			if(isset($value['imie'])){
				$row['imie'] = $this->Firewall->string($value['imie']);
			}else{
				$row['imie'] = NULL;
			}
			if(isset($value['pesel'])){
				$row['pesel'] = $this->Firewall->serialNumber($value['pesel']);
			}else{
				$row['pesel'] = NULL;
			}
			if(isset($value['kod_poczt'])){
				$row['kod_poczt'] = $this->Firewall->serialNumber($value['kod_poczt']);
			}else{
				$row['kod_poczt'] = NULL;
			}
			if(isset($value['miejscowosc']) ){
				$row['miejscowosc'] = $this->Firewall->string($value['miejscowosc']);
			}else{
				$row['miejscowosc'] = NULL;
			}
			if(isset($value['ul'])){
				$row['ul'] = $this->Firewall->string($value['ul']);
			}else{
				$row['ul'] = NULL;
			}
			if(isset($value['nr_b'])){
				$row['nr_b'] = $this->Firewall->string($value['nr_b']);
			}else{
				$row['nr_b'] = NULL;
			}
			if(isset($value['nr_l'])){
				$row['nr_l'] = $this->Firewall->string($value['nr_l']);
			}else{
				$row['nr_l'] = NULL;
			}
			if(isset($value['tel'])){
				$row['tel'] = $this->Firewall->telefonNumber($value['tel']);
			}else{
				$row['tel'] = NULL;
			}
			if(isset($value['email'])){
				$row['email'] = $this->Firewall->email($value['email']);
			}else{
				$row['email'] = NULL;
			}
			if(isset($value['data_od'])){
				$row['data_od'] = $this->Firewall->date($value['data_od']);
			}else{
				$row['data_od'] = null;
			}
			if(isset($value['data_do'])){
				$row['data_do'] = $this->Firewall->date($value['data_do']);
			}else{
				$row['data_do'] = null;
			}
			$this->dataIn[$key] = $row;
		}

  }

  /**
   * @var \PracownicyTable
   */
  protected $PracownicyTable;
}
