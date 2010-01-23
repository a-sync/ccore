<?php if(!defined('CORE')) { die('*'); }
global $_PAGES;


// start a plugin (pix) index on home
//$_PAGES[''] = 'uploader'; // index / home / no subdir 


$_CFG['debug'] = 1;
$_CFG['theme_cfg']['foot'] = '&copy; 2009 A. Smith'; //foot-nál felülírható


$_CFG['site_url'] = 'http://127.0.0.1/';

// javascripts to call for the theme
$_CFG['javascripts'][] = 'style/main.js';
$_CFG['javascripts'][] = 'style/sliding_toggler-1.06.js';
$_CFG['javascripts'][] = 'style/dbg_41a.js';

?>