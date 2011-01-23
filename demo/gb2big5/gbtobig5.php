<?php 
class gbTobig5{

 var $gb;
 
 function __construct()
 {
  $filename = "./gb-big5.table";
  $fp = fopen($filename, "rb");
  $this->gb = fread($fp,filesize($filename));
  fclose($fp);
 }
 
 function gb2big5($Text) {
  $this->gb;
  $Text = iconv("utf-8","gb2312",$Text);
  $max = strlen($Text)-1;
  for($i = 0; $i < $max; $i++) {
   $h = ord($Text[$i]);
   if($h >= 160) {
    $l = ord($Text[$i+1]);
    if($h==161 && $l==64) {
     $big = "¡¡"; 
    }else{
     $p = ($h-160)*510+($l-1)*2;
     $big = $this->gb[$p].$this->gb[$p+1];
    }
    $Text[$i] = $big[0];
    $Text[$i+1] = $big[1];
    $i++;
   }
  }
  return iconv("big5","utf-8",$Text);
 }
}
?>
