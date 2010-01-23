<?php if(!defined('CORE')) { die('*'); } // all plugin files shuld start with this
// hf: objektum orientáltra átírni amit 
// érdemes;
// "ami lehet, bővíthető objektumként 
// létrehozni a betöltött pluginokhoz"


// objectumot ebből
  
// check for init.php files to include
// from $p parameter's (dir) subdir's
// 
// 
// 
function plugins_dir($p, $f = 'init.php')
{
  // power show-off
  $data = array($p, $f, 'hook_unset' => true);
  plugin_call('plugins_dir', $data); // setup a sooner called file with: plugin_set('plugins_dir', 'my_plugin_function');
  // your function is called at this point with a data array as its first and only reference paramater
  // the hook_unset key at true value commands the plugin_call function to unset the hook_unset key in the data array that was sent
  
  //$p = $data[0]
  $plugs = @scandir($p);
  if($plugs)
  {
    foreach($plugs as $d)
    {
      if($d != '.' && $d != '..' && is_file($p.$d.'/'.$f))
      {
        @include_once $p.$d.'/'.$f;
      }
    }
    
    return true;
  }
  else
  {
    return false;
  }
}

// for now with global namespace support
// any function name put into the plugins
// array as a string will be called
// the second $data parameter will also 
// be passed by reference
// you can reach the global namespace
// or use the $data parameter to interact
// with the function by reference
// 
// the plugin call order is sorted by the
// array keys with ksort() eg.: a,b,0,1,2
//
// you can also unset the calls (to avoid
// loops) with the $data['hook_unset'] = 
// true; setting (but this shuld only be 
// used as a last resort; avoid recalling
// the plugins when not needed, by 
// a local checkpoint :)) 
$_PLUGINS = array();
function plugin_call($hook, &$data = false)//, $unset = false)
{
  global $_PLUGINS;
  
  //print_r($_PLUGINS);
  
  if(is_array($_PLUGINS[$hook]))
  {
    ksort($_PLUGINS[$hook]); // a, b, c, 1, 2, 3
    
    foreach($_PLUGINS[$hook] as $n => $function)
    {
      if(is_string($function))
      {
        $data['n'] = $n;
        $function($data);
      }
    }
    
    if($data['hook_unset'] == true)
    {
      unset($_PLUGINS[$hook]);
    }
    
    return true;
  }
  else
  {
    return false;
  }
}

// use this to interact easy with
// the plugins array in the global
// namespace
//
// return (void)
function plugin_set($hook, $function, $order = false)
{
  global $_PLUGINS;
  if(isset($_PLUGINS[$hook][$order])) { $_PLUGINS[$hook][] = $function; }
  elseif($order == false) { $_PLUGINS[$hook][] = $function; }
  else { $_PLUGINS[$hook][$order] = $function; }
}
// objectum vége


function valid_url($str)
{
  // ha nincs http, https vagy ftp protokol, addjon hozzá http-t
  if(!preg_match("!((http(s?)://)|(ftp://))!i", $str)) { $str = 'http://'.$str; }

  if(preg_match(
    // -- protocol tag vagy www
    "!(((http(s?)://)|(ftp://)|(www\.))".
    // -- host maradéka, topdomain 2-6 karakter
    "([-a-z0-9.]{2,}\.[a-z]{2,6}".
    // -- port (ha van)
    "(:[0-9]+)?)".
    // -- mappa (ha van)
    "((/([^\s]*[^\s.,\"'])?)?)".
    // -- paraméterek (ha van, de kérdőjellel kell kezdődnie)
    "((\?([^\s]*[^\s.,\"'])?)?))!i",
    // -- amiben keresünk
    $str)) {

    //echo '<pre>'.print_r($data, true).'</pre>'; exit; //debug

    return $str;
  }

  return false;
}

function group_arrays($arrays, $key, $unique = false) {
  if($unique) {
    foreach($arrays as $array) {
      $group[$array[$key]] = $array;
    }
  }
  else {
    foreach($arrays as $array) {
      $group[$array[$key]][] = $array;
    }
  }

  return $group;
}

function dbg($k, $v = 0)
{
  // plugin call for geshi
  global $_ERRORS;
  $_ERRORS['dbg'] .= $k.' - '.highlight_string('<?php '."\n".var_export($v, true)."\n".'; ?>', true);
  //highlight_string('< ?php die(); ? >');
}

?>