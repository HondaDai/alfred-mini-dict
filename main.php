<?php
require('workflows.php');
require('translate.php');

function main($query){
  $w = new Workflows();

  $url = "http://dict.cn/apis/suggestion.php?q=$query";
  $data = $w->request($url);
  $data = str_replace("&nbsp;", "", $data);
  $data = str_replace(";", "／", $data);
  $json = json_decode( $data );

  function sort_by_g_len($a, $b) {
    return strlen($a->g) > strlen($b->g);
  }

  usort($json->s, "sort_by_g_len");

  foreach($json->s as $k => $v) {
    $v->e = gb2312_to_big5($v->e);
    $j = json_encode($v);
    $w->result( $k.$j, $j, $v->g.' - '.$v->e, '', 'icon.png');
  }
  $w->result( time(), '', '已無資料', '', 'icon.png');
  return $w->toxml();
}
?>