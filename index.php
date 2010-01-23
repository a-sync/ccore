<?php
// the init itself (every server call hits here (~ .htaccess file))
//phpinfo();//better safe then sorry...
//die();

// global namespace (note: adv. config)
//error_reporting(0); // first things first, 
error_reporting(E_ALL ^ E_NOTICE);


define( 'CORE' , './core/'); // you can move the core to the root
define( 'PLUGINS' , './plugins/'); // you can move the plugins to the root (note: any file (.js, .css, images) included to a plugin shuld be called from the THEMES.PAGE dir thus creating a theme dir for a plugin)

define( 'THEMES' , './themes/');

// global namespace variables
$_ERRORS = false; // for core errors, and also for different debugging features
$_CFG = false; // configuration read from files
$_PAGES = false; // page settings read from plugins (and the theme)

require(CORE . 'functions.php');//main plugin, $_ERRORS
//print_r($_POST);
require(CORE . 'config.php'); // default config file, $_CFG

// core plugins
require(CORE . 'ccfg.php'); // global config (Array()) saved in file / function // ./configs/ dir, $_CCFG (declared inside)

require(CORE . 'template.php'); // minimal template handling function (needs to be expanded (-- with in mind that themes are handled on the same base as plugins)), $_PAGES
// ha nincs megfelelő page a plugin által foglalva a page a default theme indexet hivja meg

require(CORE . 'mysql.php'); // session handling, $_DB (declared inside)

require(CORE . 'users.php'); // session handling, $_USERS (declared inside), $_USER, cookie handling

//require('db.php');

//$data['_POST'] = $_POST;
//$data['_GET'] = $_GET;
// additional plugins used as core
//
//$_USER = false;
$data = false;
plugin_call('core', $data); // plugins from core shuld have access before anything else

define( 'DOMAIN' , 'http://' . $_SERVER['HTTP_HOST'] . '/'); // this is the called dir from root
define( 'PAGE' , htmlspecialchars(array_shift(explode('/', substr($_SERVER['REQUEST_URI'], 1), 2)))); // this is the called dir from root
define( 'THEME' , THEMES.$_CFG['theme'].'/'); // the selected themes directory (themes shuld have init.php, and index.php files)
//define( 'REQ' , strstr(substr($_SERVER['REQUEST_URI'], 1), '/')); // this is the request starting without the page
define( 'REQ' , substr($_SERVER['REQUEST_URI'], 1)); // this is the full request starting with the page

// load plugins
plugins_dir(PLUGINS);
$data['_ERRORS'] = $_ERRORS;
plugin_call('init', $data);

// load theme
//$data['t'] = @die(THEME . 'init.php');
$data['t'] = include(THEME . 'init.php'); // theme init call (plugin hooks for home and / or $_PAGE additions (you can control the PAGE routing from the theme init file))

// routing to files
$data['i'] = false;
if(isset($_PAGES[PAGE])) // $_PAGES shuld be used by plugins
{
  plugin_call('page', $data);
  
  $data['i'] = include(PLUGINS . $_PAGES[PAGE] . '/index.php');
  if($data['i'] == false) // if the requested PAGE is not set, or the include fails: error
  {
    $_ERRORS[404] = 'no_plugin'; // plugins can end here, so invalid url's are handled properly by the theme
  }
}
else //if(PAGE != '')
{
  $data['i'] = false;
  
  $_ERRORS[404] = 'no_page'; // your basic 404; no plugin called by this root (PAGE name)
  // you can use this in the theme index to route the PAGE based on theme files and include them
}

$data['_ERRORS'] = $_ERRORS;
// set plugins for 404 and root calls
if(isset($_ERRORS[404]))
{
  plugin_call('home', $data, true);
  include(THEME . 'index.php'); // theme index call
}

plugin_call('end', $data);
// log nagyon fontos !core
// dbg plugin !core
// lang support
// plugin, ami megjeleníti a begyűjtött plugins tömböt és engedi módosítani a sorrendet, azzal hogy lementi
// (cfg plugin
?>