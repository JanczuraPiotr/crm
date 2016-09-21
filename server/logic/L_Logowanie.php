<?php
use crmsw\lib\a\BusinessLogic;
use pjpl\db\Where;
/**
 * @done 2014-10-19 Przebudowa Table do obsługi zapytań preparowanych
 * @done 2014-10-19 Duża przebudowa DependencyTableRecord
 * @todo \pjpl\a\BusinessLogic <<< \crmsw\lib\a\BusinessLogic z usunięciem dziesdziczenia po klasie ...\beta\BusinessLogic
 */
class L_Logowanie extends BusinessLogic{

  protected function logic() {
		echo '<pre>'.__FILE__.' '.__LINE__.'<br>'; print_r($this->DB); echo '</pre>';
    $PracownicyTable = $this->DB->tablePracownicy();
		$WhereLogin = new Where([
						[
										'attribute' => 'login',
										'operator'  => '=',
										'value'     => $this->dataIn['login']
						],[
										'attribute' => 'haslo',
										'operator'  => '=',
										'value'     => sha1($this->dataIn['haslo'])
 						]
		]);
		$PracownicyTable->where($WhereLogin)->load();

    if($PracownicyTable->count() === 1 ){

      // Podano poprawne dane logowania "jakiegoś pracownika". Należy sprawdzić jego typ
			$_SESSION['USER_LOGIN'] = $this->dataIn['login'];
			$rPracownik = $PracownicyTable->getRecordIfOne();
			if($rPracownik !== NULL){
				// Znaleziono jednego pracownika o podanych parametrach logowania
				$_SESSION['FIRMA_ID'] = $rPracownik->getEncja()->getFirmaId();
				$this->dataOut['firma_id'] = $rPracownik->getEncja()->getFirmaId();
				$_SESSION['PRACOWNIK_ID'] = $rPracownik->getId();
				$_SESSION['PRACOWNIK_NAZWA'] = $rPracownik->getEncja()->getNazwisko().' '.$rPracownik->getEncja()->getImie();
				$this->dataOut['pracownik_id'] = $rPracownik->getId();
				$this->dataOut['pracownik_nazwa']= $_SESSION['PRACOWNIK_NAZWA'];

				// Sprawdzamy czy jest Zarządcą
				$tZarzady = $this->DB->tableZarzady();
				$WhereZarzadca = new Where([
								[
												'attribute' =>	'pracownik_id',
												'operator'  => '=',
												'value'     => $rPracownik->getId()
							  ],[
												'attribute' => 'data_do',
												'operator'  => '=',
												'value'     => 'NULL'
								]
				]);
				$tZarzady->where($WhereZarzadca)->load();
				$rZarzadca = $tZarzady->getRecordIfOne();

				if($rZarzadca !== NULL){
					$_SESSION['USER_STATUS'] = $rZarzadca->getEncja()->getStatus();
					$tStanowiska = $this->DB->tableStanowiska();
					$WhereStanowiskoZarzadcy = new Where([
									[
													'attribute' => 'pracownik_id',
													'operator'  => '=',
													'value'     => $rPracownik->getId()
									]
					]);
					$tStanowiska->where($WhereStanowiskoZarzadcy)->load();
					$rStanowisko = $tStanowiska->getRecordIfOne();

					if($rStanowisko !== null){
						$_SESSION['PLACOWKA_ID'] = $rStanowisko->getEncja()->getPlacowkaId();
						$_SESSION['STANOWISKO_ID'] = $rStanowisko->getId();
					}else{
						$_SESSION['PLACOWKA_ID'] = NULL;
						$_SESSION['STANOWISKO_ID'] = NULL;
					}
					$this->dataOut['user_status'] = $_SESSION['USER_STATUS'];
					$this->dataOut['stanowisko_id'] =$_SESSION['STANOWISKO_ID'];
					$this->dataOut['placowka_id'] = $_SESSION['PLACOWKA_ID'];
					$this->success = TRUE;
					return ;
				}

				$tStanowiska = $this->DB->tableStanowiska();
				$WhereStanowisko = new Where([
								[
												'attribute' => 'pracownik_id',
												'operator'  => '=',
												'value'     => $rPracownik->getId()
								]
				]);
				$tStanowiska->where($WhereStanowisko)->load();
				$rStanowisko = $tStanowiska->getRecordIfOne();

				if($rStanowisko !== NULL){

					$tStatusyStanowisk = $this->DB->tableStatusyStanowisk();
					$rStatusStanowiska = $tStatusyStanowisk->getRecord($rStanowisko->getEncja()->getStatusStanowiskaId());

					$_SESSION['STANOWISKO_ID'] = $rStanowisko->getId();
					$_SESSION['PLACOWKA_ID'] = $rStanowisko->getEncja()->getPlacowkaId();
					$_SESSION['USER_STATUS'] = $rStatusStanowiska->getEncja()->getKod();
					$this->dataOut['user_status'] = $_SESSION['USER_STATUS'];
					$this->dataOut['stanowisko_id'] =$_SESSION['STANOWISKO_ID'];
					$this->dataOut['placowka_id'] = $_SESSION['PLACOWKA_ID'];
					$this->success = TRUE;
					return;
				}
			}

    }else{

			// administratorzy
			$AdministratorTable = $this->DB->tableAdministratorzy();
			$AdministratorTable->where($WhereLogin)->load();
			if($AdministratorTable->count() === 0){

				unset($_SESSION['USER_STATUS']);
				unset($_SESSION['FIRMA_ID']);
				unset($_SESSION['STANOWISKO_ID']);
				unset($_SESSION['PRACOWNIK_ID']);
				unset($_SESSION['PRACOWNIK_NAZWA']);
				unset($_SESSION['PLACOWKA_ID']);

				$this->success = FALSE;

			}  else {

				$_SESSION['USER_LOGIN'] = $this->dataIn['login'];
				if($AdministratorTable->getRecordFirst()->getId() == 1){
					$_SESSION['USER_STATUS'] = CRM::ADMIN_SUPER;
					$_SESSION['FIRMA_ID'] = null;
					$_SESSION['STANOWISKO_ID'] = null;
					$_SESSION['PRACOWNIK_ID'] = null;
					$_SESSION['PRACOWNIK_NAZWA'] = null;
					$_SESSION['PLACOWKA_ID'] = NULL;
					$this->dataOut['user_status'] = $_SESSION['USER_STATUS'];
					$this->dataOut['firma_id'] = null;
					$this->dataOut['stanowisko_id'] = null;
					$this->dataOut['pracownik_id'] = NULL;
					$this->dataOut['pracownik_nazwa'] = NULL;
					$this->dataOut['placowka_id'] = NULL;
					$this->success = TRUE;
				}else{
					$_SESSION['USER_STATUS'] = CRM::ADMIN_ZWYKLY;
					$_SESSION['FIRMA_ID'] = null;
					$_SESSION['STANOWISKO_ID'] = null;
					$_SESSION['PRACOWNIK_ID'] = null;
					$_SESSION['PRACOWNIK_NAZWA'] = NULL;
					$_SESSION['PLACOWKA_ID'] = NULL;
					$this->dataOut['user_status'] = $_SESSION['USER_STATUS'];
					$this->dataOut['firma_id'] = null;
					$this->dataOut['stanowisko_id'] = null;
					$this->dataOut['pracownik_id'] = NULL;
					$this->dataOut['pracownik_nazwa'] = null;
					$this->dataOut['placowka_id'] = NULL;
					$this->success = TRUE;
				}

			}

		}
  }
  public function getJson() {
    if($this->success){
      return json_encode(array('success'=>true,'msg'=>'Zalogowano','data'=>$this->dataOut));
    }else{
      return json_encode(array('success'=>false,'msg'=>'Nieudana próba zalogowania','data'=>  $this->dataOut));
    }
  }

  /**
   * @param type $_request
   * @throws EBadIn - brak danych do logowania
   */
  public function fromRequest(&$_request) {
    if(isset($_request['login'])){
      $this->dataIn['login'] = $this->Firewall->login($_request['login']);
    }  else {
      $this->dataIn['login'] = null;
    }
    if(isset($_request['password'])){
      $this->dataIn['haslo'] = $this->Firewall->password($_request['password']);
    }else{
      $this->dataIn['haslo'] = null;
    }
    if($this->dataIn['login'] === null || $this->dataIn['haslo'] === null){
      throw new \pjpl\depreciate\EBadIn(__CLASS__, __METHOD__, 'login&haslo', 'null');
    }
  }

}