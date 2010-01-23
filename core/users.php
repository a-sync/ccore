<?php if(!defined('CORE')) { die('*'); }
// core call
$_USERS = array(); // users setting, classes, overwrites 


# $_SESSION, $_COOKIE #
$_ERRORS['session_start'] = @session_start();
if($_CFG['debug'] == 1) dbg(__FILE__ . ':' . __LINE__ . ' @session_start == ', $_ERRORS['session_start']);


// users.php
plugin_set('page', 'users_set_var', 'users'); // theme init file
plugin_set('home', 'users_set_var', 'users'); // no page to call
function users_set_var(&$data)
{
  global $_USERS;
  ccfg_reload($data, 'the_users', '_USERS');
  
}

plugin_set('core', 'users_load', 'users_load'); // plugin init files

plugin_set('init', 'users_load', 'users_load'); // just config.php and core

plugin_set('page', 'users_load', 'users_load'); // theme init file
plugin_set('home', 'users_load', 'users_load'); // no page to call
function users_load($data, $sql = false)
{
  global $_USERS;
  
  //session_load($data); //van-e felhasználó bejelentkezve
  
  // frontend info
  if($_SESSION['user'] == false) // inkabb isset --?!?
  {
    $_SESSION['user']['level'] = false;// backend needed info
  }
  
  if(is_array($_USERS['classes'][$_SESSION['user']['class']])) $_SESSION['user'] = array_merge($_SESSION['user'], $_USERS['classes'][$_SESSION['user']['class']]); // class beállítások, hozzáadások (numerikus indexek)
  
  if(is_array($_USERS['names'][$_SESSION['user']['name']])) $_SESSION['user'] = array_merge($_SESSION['user'], $_USERS['names'][$_SESSION['user']['name']]); // name (egyéni) beállítások, hozzáadások (numerikus indexek)

  //if(is_array($_USERS['sessions'][$_SESSION['sessions']])) $_SESSION['user'] = array_merge($_SESSION['user'], $_USERS['sessions'][$_SESSION['user']['session']]); // session beállítások, hozzáadások (numerikus indexek)
  // 
  
  
  // _DB-be iras ($sql === true)
  // _USERS['sql']-ben meghatarozott kulcsokat irod csak bele (id, session, name, email, level, class ... +)
  // _USERS['sql'] => array(
  // 'id' => 'id',//int, auto_inc, unsigned
  // 'session' => 'hash',//tinytext
  // 'name' => 'tinytext',
  // 'email' => 'hash',//tinytext
  // 'level' => 'tinyint', unsigned
  // 'class' => 'tinytext',
  // );
  
}


function login_form($submit = false)
{
  global $_USERS, $_ERRORS;

  if($submit == false)
  {
    $form_elements = array('Name:' => array('input' => 'text',
                                            'value' => '',
                                            'name' => 'name',
                                            'tabindex' => '1',
                                            'maxlength' => '32',
                                            'accesskey' => 'n'),
                           'Pass:' => array('input' => 'password',
                                            'value' => '',
                                            'name' => 'pass',
                                            'tabindex' => '2',
                                            'maxlength' => '32',
                                            'accesskey' => 'p'),
                           0 => array('input' => 'submit',
                                             'tabindex' => '3',
                                             'value' => 'Login',
                                             'name' => 'login_submit',
                                             //'accesskey' => 'l' // Enter...
                                             ),
                           );
    if($_SESSION['user']['level'] == false)
    {
      formmaker('login', $form_elements);
      if(isset($_ERRORS['login']) && count($_ERRORS['login']) > 0)
      {
        echo '<div class="login_errors">'.implode('<br/>', $_ERRORS['login']).'</div>';
      }
      return true;
    }
    else
    {
      return false;
    }
  }
  elseif($_POST['login_submit'] != '' && $_POST['name'] != '' && $_POST['pass'] != '')
  {
    if(ctype_alnum(str_replace('_', '', $_POST['name'])) && is_array($_USERS['names'][$_POST['name']]))
    {
      // hook
      if(isset($_USERS['names'][$_POST['name']]) && $_USERS['names'][$_POST['name']]['pass'] == $_POST['pass']) // md5( $_CFG['db']['salt'] ) -- db ident
      {
        //die(print_r($_USERS, true));
        //$_SESSION['user'] = $_USERS['names'][$_POST['name']];
        $_SESSION['user']['name'] = $_POST['name'];
        $_SESSION['user']['level'] = 1;
        users_load();
        
        location("http://".$_SERVER['HTTP_HOST'].'/');
      }
      /// db
      else
      {
        $_ERRORS['login']['bad_pass'] = 'Bad pass.';
      }
    }
    else
    {
      $_ERRORS['login']['unknown_user'] = 'Unknown user.';
    }
  }
}

function cookie($name, $value, $life = 0, $page = '/', $domain = '', $no_js = false)
{
die('cookie () -- unfinished');
  if($value === false)
  {
    $life = -3600 * 24;
    unset($_COOKIE[$name]); 
  }
  
  if($domain == '')
  {
    $domain = '.'.$_SERVER['REMOTE_HOST'];
  }
dbg('$domain ', $domain);// DOMAIN vagy HOSt vagy valami már van
  if($life === false) $expire = 0;
  else $expire = time() + $life;
  
  $path = $page;
  $secure = $https = false; // $_CFG['https_cookies'] = false;
  $httponly = $no_js;
  
  return setcookie($name, $value, $expire, $path, $domain, $secure, $httponly);
}


function user_logout($data = false)
{
  $_SESSION = false;
  session_destroy();
  
  session_start();
  //$_SESSION['messages'][] = 'You logged out.';
  
  location(DOMAIN); // refresh

  // WTF? --\/--
/*
  global $$global;
  $var = false;
  $var_session = strtolower($global);
  $var_cookie = $var_session.'_session';
  
  if(isset($_SESSION[$var_session]))
  {
    if(isset($_COOKIES[$var_cookie]) && $_SESSION[$var_session]['session'] == $_COOKIES[$var_cookie])
    {
      $var = $_SESSION[$var_session];
      
       // plugin session_reload // data var_session
    }
  }
  elseif(isset($_COOKIES[$var_cookie]) && ctype_alnum($_COOKIES[$var_cookie]) && strlen($_COOKIES[$var_cookie]) == 32)
  {
    $_SESSION[$var_session]['session'] = strtolower($_COOKIES[$var_cookie]);
    $var = $_SESSION[$var_session];
    
    $var['settings'] = false;
    // plugin session_load_new
    
    //$var['settings'] = sqlq_assoc("SELECT * FROM `{$_DB['host']}`.`users` WHERE `{$_DB['host']}`.`session` = '$var['session']' LIMIT 1", $key);
    // name (== md5($email . $_USERS['email_salt']))
    // function sqlq_assoc($q) return mysq_fetch_assoc(mysql_query( $q ))[$key];
    
    //$var['settings'] = array_merge($var, unserialize($var['settings']));
    
    // [settings] urites
    // pass resendnel uj session generalas meg belepesnel is
    //if($var['session'] == $var['session'])
    //{
    //  $_SESSION[$var_session] = $var;
    //}
  }
  
  $$global = $var;
*/
}

?>