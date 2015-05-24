<?php

namespace pjpl\depreciate;

class Arr{
  public $arr;
  /**
   * @param {Arr || array} $in
   * @throws EBadType
   */
  public function __construct(&$in = null) {
    if($in === null) {
      $this->arr = array();
      return;
    };

    $type = gettype($in);
    switch($type){
      case 'array':
          $this->arr = &$in;
        break;
      case 'object':
        $class = get_class($in);
        switch ($class) {
          case 'Arr':
            $this->arr = &$in->arr;
            break;
          default:
            $E = new EBadType(__CLASS__, __FUNCTION__, 'Arr || array', $class);
            throw $E;
            break;
        }
        break;
      default:
        $E = new EBadType(__CLASS__, __FUNCTION__, 'Arr || array', $type);
        throw $E;
    }
  }
}


?>
