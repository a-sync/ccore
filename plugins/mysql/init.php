<?php if(!defined('CORE')) { die('*'); }
global $_PAGES;

$_PAGES['db'] = 'mysql';

// geshi hívás sql_ui-ba
// kód formázáshoz
// 
plugin_set('mysql_sql_ui_code', 'sql_ui_code_geshi');
function sql_ui_code_geshi($data)
{
  if($data['query'] != '')
  {
    geshi_highlight($data['query'], 'mysql');
  }
}

?>