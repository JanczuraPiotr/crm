<?php
/**
 * @package crmsw
 * @subpackage logic
 * @author Piotr Janczura <piotr@janczura.pl>
 * @done 2014-12-19
 * @todo \pjpl\a\BusinessLogic <<< \crmsw\lib\a\BusinessLogic z usunięciem dziesdziczenia po klasie ...\beta\BusinessLogic
 */
class L_OsobyPowiazaneUpdate extends \crmsw\lib\a\BusinessLogic{
  public function __construct() {
    parent::__construct();
    $this->OsobyPowiazaneTable = $this->DB->tableOsobyPowiazane();
  }

  protected function logic() {
    foreach ($this->dataIn as $key => $row) {
			try{
				$Record = $this->OsobyPowiazaneTable->getRecord($row['id']);
				$OsobaPowiazana = $Record->getEncja();

				if( $row['klient_id'])   { $OsobaPowiazana->klient_id   = $row['klient_id'];   }
				if( $row['nazwa'])       { $OsobaPowiazane->nazwa       = $row['nazwa'];       }
				if( $row['imie'])        { $OsobaPowiazana->imie        = $row['imie'];        }
				if( $row['pesel'])       { $OsobaPowiazana->pesel       = $row['pesel'];       }
				if( $row['nip'])         { $OsobaPowiazana->nip         = $row['nip'];         }
				if( $row['kod_poczt'])   { $OsobaPowiazana->kod_poczt   = $row['kod_poczt'];   }
				if( $row['miejscowosc']) { $OsobaPowiazana->miejscowosc = $row['miejscowosc']; }
				if( $row['ul'])          { $OsobaPowiazana->ul          = $row['ul'];          }
				if( $row['nr_b'])        { $OsobaPowiazana->nr_b        = $row['nr_b'];        }
				if( $row['nr_l'])        { $OsobaPowiazana->nr_l        = $row['nr_l'];        }
				if( $row['email'])       { $OSobaPowiazana->email       = $row['email'];       }
				if( $row['telkom'])      { $OsobaPowiazana->teldom      = $row['telkom'];      }
				if( $row['teldom'])      { $OsobaPowiazana->teldom      = $row['teldom'];      }
				if( $row['telpraca'])    { $OsobaPowiazana->telpraca    = $row['telpraca'];    }

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
			if(isset($value['klient_id'])){
				$row['klient_id'] = $this->Firewall->int($value['klient_id']);
			}else{
				$row['klient_id'] = null;
			}
			if(isset($value['nazwa'])){
				$row['nazwa'] = $this->Firewall->string($value['nazwa']);
			}else{
				$row['nazwa'] = null;
			}
			if(isset($value['imie'])){
				$row['imie'] = $this->Firewall->string($value['imie']);
			}else{
				$row['imie'] = null;
			}
			if(isset($value['pesel'])){
				$row['pesel'] = $this->Firewall->serialNumber($value['pesel']);
			}else{
				$row['pesel'] = null;
			}
			if(isset($value['nip'])){
				$row['nip'] = $this->Firewall->serialNumber($value['nip']);
			}else{
				$row['nip'] = null;
			}
			if(isset($value['kod_poczt'])){
				$row['kod_poczt'] = $this->Firewall->serialNumber($value['kod_poczt']);
			}else{
				$row['kod_poczt'] = null;
			}
			if(isset($value['miejscowosc'])){
				$row['miejscowosc'] = $this->Firewall->string($value['miejscowosc']);
			}else{
				$row['miejscowosc'] = null;
			}
			if(isset($value['ul'])){
				$row['ul'] = $this->Firewall->string($value['ul']);
			}else{
				$row['ul'] = '';
			}
			if(isset($value['nr_b'])){
				$row['nr_b'] = $this->Firewall->string($value['nr_b']);
			}else{
				$row['nr_b'] = null;
			}
			if(isset($value['nr_l'])){
				$row['nr_l'] = $this->Firewall->string($value['nr_l']);
			}else{
				$row['nr_l'] = null;
			}
			if(isset($value['email'])){
				$row['email'] = $this->Firewall->email($value['email']);
			}else{
				$row['email'] = null;
			}
			if(isset($value['telkom'])){
				$row['telkom'] = $this->Firewall->telefonNumber($value['telkom']);
			}else{
				$row['telkom'] = null;
			}
			if(isset($value['teldom'])){
				$row['teldom'] = $this->Firewall->telefonNumber($value['teldom']);
			}else{
				$row['teldom'] = null;
			}
			if(isset($value['telpraca'])){
				$row['telpraca'] = $this->Firewall->telefonNumber($value['telpraca']);
			}else{
				$row['telpraca'] = null;
			}
			$this->dataIn[$key] = $row;
		}
  }
  /**
   * @var OsobyPowiazaneTable
   */
  protected $OsobyPowiazaneTable;
}