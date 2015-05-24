<?php


function s(){
  $timeparts = explode (" ",microtime());
  return $timeparts[1];
}
function ms(){
  $timeparts = explode (" ",microtime());
  return bcadd (($timeparts[0]*1000),bcmul ($timeparts[1],1000));
}
function µs(){
   $timeparts = explode (" ",microtime());
  return bcadd (($timeparts[0]*1000000),bcmul ($timeparts[1],1000000));
}
function microDate(){
  $µTime=microtime();
  list($µs,$s)=explode(' ',$µTime);
  return gmdate('Y-m-d H:i:s',$s).'.'.sprintf('%06d',substr($µs,2,6));
}
?>
