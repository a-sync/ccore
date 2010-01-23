<?php
if(!defined('CORE')) { die('*'); }
//dbg(THEMES . THEME .'index.php _POST', $_POST);


if(PAGE == '' || PAGE == 'home') // home
{
  head($data);
  echo '$-- '. __FILE__ .':'. __LINE__ ;
  dbg('$_SESSION ', $_SESSION);
  
  foot($data);
}
elseif('noscript' == PAGE)
{
  include THEME.'noscript.php';
}
else
{
  include THEME.'404.php';
}




?>