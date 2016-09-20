<?php
use pjpl\db\Where;
/**
 * @package crmsw
 * @subpackage logic
 * @author Piotr Janczura <piotr@janczura.pl>
 * @done 2014-10-19 Przebudowa Table do obsługi zapytań preparowanych
 * @done 2014-10-19 Duża przebudowa DependencyTableRecord
 * @todo \pjpl\a\BusinessLogic <<< \crmsw\lib\a\BusinessLogic z usunięciem dziesdziczenia po klasie ...\beta\BusinessLogic
 */
class L_ZmianaHasla extends \crmsw\lib\a\BusinessLogic{

	public function __construct() {
		parent::__construct();
		$this->tAdministratorzy = $this->DB->tableAdministratorzy();
		$this->tPracownicy = $this->DB->tablePracownicy();
	}

	protected function logic() {

		$this->return_msg = '';

		if(($this->dataIn['password1'] !== $this->dataIn['password2'])){
			$this->return_msg = 'podane hasła są różne';
			$this->success = FALSE;
		}
		if($this->dataIn['password1'] === NULL){
			$this->return_msg = 'pusty pierwszy wzorzec nowego hasła';
			$this->success = false;
		}
		if($this->dataIn['password2'] === NULL){
			$this->return_msg = 'pusty drugi wzorzec nowego hasła';
			$this->success = false;
		}
		if(!$this->success){
			return;
		}

		$login_in_admin_id = NULL;
		$login_in_pracownk_id = NULL;
		if($this->dataIn['login'] !== null){
			// Sprawdzam czy podany login jest już znany w systemie.
			// Jeżlie nikt nie ma tego loginu to bez obaw można go nadać podczas zmiany danych logowania.
			$WhereAdministratorzy = new Where([
							[
											'attribute' => 'login',
											'operator'  => '=',
											'value'     => $this->dataIn['login']
							]
			]);
			$this->tAdministratorzy->where($WhereAdministratorzy)->load();
			$rAdministrator = $this->tAdministratorzy->getRecordIfOne();
			if($rAdministrator !== NULL){
				// Login podany jako nowy jest już przypisany do administratora
				$login_in_admin_id = $rAdministrator->getId();
			}else{
				$WherePracownicy = new Where([
								[
												'attribute' => 'login',
												'operator'  => '=',
												'value'     => $this->dataIn['login']
								]
				]);
				$this->tPracownicy->where($WherePracownicy)->load();
				$rPracownik = $this->tPracownicy->getRecordIfOne();
				if($rPracownik !== NULL){
					// Login podany jako nowy jest już przypisany do użytkownika.
					$login_in_pracownk_id = $rPracownik->getId();
				}
				$this->tPracownicy->where(new Where());
			}
		}

		try{
			if ((int)$_SESSION['USER_STATUS'] === CRM::ADMIN_SUPER /* || $_SESSION['USER_TYP'] === CRM::ADMIN_ZWYKLY */ ) { // Kto zmienia hasło - admin może zmieniać wszystkim
				$WhereAdministratorzy = new Where([
								[
												'attribute' => 'login',
												'operator'  => '=',
												'value'     => $_SESSION['USER_LOGIN']
								]
				]);
				$this->tAdministratorzy->where($WhereAdministratorzy)->load();
				$rAdministrator = $this->tAdministratorzy->getRecordIfOne();

				if($this->dataIn['pracownik_id'] === NULL) {
					// Admin zmienia swoje hasło.

					if($this->dataIn['login'] !== NULL){
						// Jeżeli to nowe haslo jest już w systemie użyte przez innego użytkownika należy przerwać operacje

						if($login_in_admin_id !== NULL){
							// Sprawdzam do jakiego admina należy zaproponowane hasło. Jeżeli należy do tego admina, który jest alogowany to login nie będzie powtórzony w systemie.
							$Administratorzy = $this->DB->tableAdministratorzy();
							if($rAdministrator->getId() !== $login_in_admin_id){
								// Inny admin ma już ten login
								$this->dataOut['login'] = '0 podany login istnieje już w systemie';
								$E = new \pjpl\depreciate\EDBNotUnique(__CLASS__, __FUNCTION__, $this->tAdministratorzy->getDI()->tableName(), 'login', $this->dataIn['login']);
								throw $E;
							}
						}else if($login_in_pracownk_id !== NULL){
							// Jakiś pracownik ma ten login więc nie można go przypisać administratororwi
							$this->dataOut['login'] = '0 podany login istnieje już w systemie';
							$E = new \pjpl\depreciate\EDBNotUnique(__CLASS__, __FUNCTION__, $this->tPracownicy->getDI()->tableName(), 'login', $this->dataIn['login']);
							throw $E;
						}else{
							// Nikt nie używa tego loginu.
						}
					}

					if($this->dataIn['login'] !== NULL){
						$rAdministrator->getEncja()->setLogin($this->dataIn['login']);
					}
					$rAdministrator->getEncja()->setHaslo(sha1($this->dataIn['password1']));
					$rAdministrator->updateImmediately();
					$_SESSION['USER_LOGIN'] = $rAdministrator->getEncja()->getLogin();

				}else{
					// Admin zmienia hasło pracownikowi

					if($this->dataIn['login'] !== NULL){
						if($login_in_admin_id !== NULL){
							$this->dataOut['login'] = ' podany login istnieje już w systemie';
							$E = new \pjpl\depreciate\EDBNotUnique(__CLASS__, __FUNCTION__, $this->tAdministratorzy->getDI()->tableName(), 'login', $this->dataIn['login']);
							throw $E;
						}else if($login_in_pracownk_id !== NULL){
							// Jakiś pracownk ma już ten login. Może to być ten sam pracownik, który wpisał do zmiany ten sam login który posida lub całkiem inny pracownik
							if((int)$login_in_pracownk_id !== (int)$this->dataIn['pracownik_id']){
								// Inny pracownik posiada login zaproponowany dla tego pracownika któremu nadawany jest nowy login
								$this->dataOut['login'] = ' podany login istnieje już w systemie';
								$E = new \pjpl\depreciate\EDBNotUnique(__CLASS__, __FUNCTION__, $this->tPracownicy->getDI()->tableName(), 'login', $this->dataIn['login']);
								throw $E;
							}else{
								// Ten login posiada ten sam klient któremu zmieniane jest hasło
							}
						}else{
							// Nikt inny nie ma tego loginu
						}
					}
					$rPracownik = $this->tPracownicy->getRecord($this->dataIn['pracownik_id']);
					if($rPracownik !== NULL){
						if($this->dataIn['login'] !== NULL){
							$rPracownik->getEncja()->setLogin($this->dataIn['login']);
						}
						$rPracownik->getEncja()->setHaslo(sha1($this->dataIn['password1']));
						$rPracownik->updateImmediately();
					}else{
						$this->success = false;
						$this->dataOut['pracownik_id'] = 'błędny pracownik';
					}
				}

			}else if( (int)$_SESSION['USER_STATUS'] === CRM::ZARZAD_PREZES || (int)$_SESSION['USER_STATUS'] === CRM::ZARZAD_CZLONEK){

				if($this->dataIn['login'] === NULL){
					if($login_in_admin_id !== NULL){
						// Proponowany login ma już administrator
						$this->dataOut['login'] = ' podany login istnieje już w systemie';
						$E = new \pjpl\depreciate\EDBNotUnique(__CLASS__, __FUNCTION__, $this->tAdministratorzy->getDI()->tableName(), 'login', $this->dataIn['login']);
						throw $E;
					}else if($login_in_pracownk_id !== NULL){
						// Jakiś pracownk ma już ten login. Może to być ten sam pracownik, który wpisał do zmiany ten sam login który posida lub całkiem inny pracownik
						if($login_in_pracownk_id !== $this->dataIn['pracownik_id']){
							// Inny pracownik posiada login zaproponowany dla tego pracownika któremu nadawany jest nowy login
							$this->dataOut['login'] = ' podany login istnieje już w systemie';
							$E = new \pjpl\depreciate\EDBNotUnique(__CLASS__, __FUNCTION__, $this->tPracownicy->getDI()->tableName(), 'login', $this->dataIn['login']);
							throw $E;
						}else{
							// Ten login posiada ten sam klient któremu zmieniane jest hasło
						}
					}else{
						// Nikt inny nie ma tego loginu
					}
				}

				$rPracownik = $this->tPracownicy->getRecord($this->dataIn['pracownik_id']);
				if($rPracownik !== NULL){
					if($this->dataIn['login'] !== NULL){
						$rPracownik->getEncja()->setLogin($this->dataIn['login']);
					}
					$rPracownik->getEncja()->setHaslo(sha1($this->dataIn['password1']));
					$rPracownik->updateImmediately();
				}else{
					$this->success = false;
					$this->dataOut['pracownik_id'] = 'błędny pracownik';
				}

			}else if((int)$_SESSION['PRACOWNIK_ID'] === (int)$this->dataIn['pracownik_id']){
				// Użytkownik może zmieniać tylko hasło samemu sobie

				if($login_in_admin_id !== NULL){
					// Login ma już administrator
					$this->dataOut['login'] = ' podany login istnieje już w systemie';
					$E = new \pjpl\depreciate\EDBNotUnique(__CLASS__, __FUNCTION__, $this->tAdministratorzy->getDI()->tableName(), 'login', $this->dataIn['login']);
					throw $E;
				}else if($login_in_pracownk_id !== NULL){
					// Jakiś pracownk ma już ten login. Może to być ten sam pracownik, który wpisał do zmiany ten sam login który posida lub całkiem inny pracownik
					if($login_in_pracownk_id !== $this->dataIn['pracownik_id']){
						// Inny pracownik posiada login zaproponowany dla tego pracownika któremu nadawany jest nowy login
						$this->dataOut['login'] = ' podany login istnieje już w systemie';
						$E = new \pjpl\depreciate\EDBNotUnique(__CLASS__, __FUNCTION__, $this->tPracownicy->getDI()->tableName(), 'login', $this->dataIn['login']);
						throw $E;
					}else{
						// Ten login posiada ten sam klient któremu zmieniane jest hasło
					}
				}else{
					// Nikt inny nie ma tego loginu
				}

				$rPracownik = $this->tPracownicy->getRecord($this->dataIn['pracownik_id']);
				if($rPracownik !== NULL){
					if($this->dataIn['login'] !== NULL){
						$rPracownik->getEncja()->setLogin($this->dataIn['login']);
					}
					$rPracownik->getEncja()->setHaslo(sha1($this->dataIn['password1']));
					$rPracownik->updateImmediately();
				}else{
					$this->success = false;
					$this->dataOut['pracownik_id'] = 'błędny pracownik';
				}
			}

			$this->dataOut[] = array('success'=>true);
		} catch (Exception $E) {;
		echo '<pre>'.__FILE__.'::'.__LINE__.'<br>'.PHP_EOL.print_r($E->getFile()." ".$E->getLine(),TRUE).'</pre>'.PHP_EOL;

			$this->success = false;
			$this->catchLogicException($E);
		}
	}

	public function fromRequest(&$_request) {

		if(isset($_request['pracownik_id']) && !empty($_request['pracownik_id'])){
			$this->dataIn['pracownik_id'] = $this->Firewall->int($_request['pracownik_id']);
		}else{
			$this->dataIn['pracownik_id'] = null;
		}

		if(isset($_request['login']) && !empty($_request['login'])){
			$this->dataIn['login'] = $this->Firewall->login($_request['login']);
//		}else if(isset($_SESSION['USER_LOGIN'])){
//			$this->dataIn['login'] = $_SESSION['USER_LOGIN'];
		}else{
			$this->dataIn['login'] = NULL;
		}

		if(isset($_request['password1'])){
			$this->dataIn['password1'] = $this->Firewall->password($_request['password1']);
		}else{
			$this->dataIn['password1'] = NULL;
		}

		if(isset($_request['password2'])){
			$this->dataIn['password2'] = $this->Firewall->password($_request['password2']);
		}else{
			$this->dataIn['password2'] = NULL;
		}
	}

	public function getJson() {
		return json_encode(array('success'=>  $this->success,'message'=> $this->return_msg,'data'=> $this->dataOut));
	}

	/**
	 * @var AdministratorzyTable
	 */
	protected $tAdministratorzy;
	/**
	 * @var PracownicyTable
	 */
	protected $tPracownicy;
}