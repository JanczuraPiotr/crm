<?php
namespace crmsw\lib\a;
use pjpl\Firewall;
/**
 * @done 2014-12-16
 */
abstract class BusinessLogic extends \pjpl\a\BusinessLogic{
  public function __construct(){
    parent::__construct(\crmsw\lib\db\DB::getInstance(), Firewall::getInstance());
  }
}
