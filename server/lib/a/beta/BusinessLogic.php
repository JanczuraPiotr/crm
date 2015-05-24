<?php
namespace crmsw\lib\a\beta;
use pjpl\Firewall;

/**
 * @todo \pjpl\a\BusinessLogic <<< \crmsw\lib\a\BusinessLogic z usunięciem dziesdziczenia po klasie ...\beta\BusinessLogic
 * @todo Brak obsługi znaczników czasowych rekordów
 */
abstract class BusinessLogic extends \pjpl\a\beta\BusinessLogic{
  public function __construct(){
    parent::__construct(\crmsw\lib\db\DB::getInstance(), Firewall::getInstance());
  }
}
