<?php if(!defined('CORE')) { die('*'); }
// core call

$_DB = array(); // users setting, classes, overwrites 

db_set_var();
$_ERRORS['mysql_connect'] = @mysql_connect($_DB['host'], $_DB['user'], $_DB['pass']);
$_ERRORS['mysql_select_db'] = @mysql_select_db($_DB['database']);
if($_CFG['debug'] == 1)
{
  dbg(__FILE__ . ':' . __LINE__ . ' @mysql_connect == ', $_ERRORS['mysql_connect']);
  dbg(__FILE__ . ':' . __LINE__ . ' @mysql_select_db == ', $_ERRORS['mysql_select_db']);
}



// mysql.php
//plugin_set('init', 'db_set_var', 'db'); // theme init file
plugin_set('core', 'db_set_var', 'db'); // theme init file
plugin_set('home', 'db_set_var', 'db'); // no page to call
function db_set_var(&$data = false)
{
  global $_DB;
  ccfg_reload($data, 'the_db', '_DB');
}



function sql($q)
{
  global $_ERRORS;
  $q = @mysql_query($q);
  $en = @mysql_errno();
  $e = @mysql_error();
  
  if(!$q) $_ERRORS['sql']['SQL error ['.$en.']'] = $e;
  
  if(!$q && $_CFG['debug'] == 1) dbg( 'SQL error ['.$en.']', $e);
  if($_CFG['debug'] == 1) dbg( 'SQL query ('.md5($q).')', $q);
  
  return $q;
}

function sql_errors()
{
  global $_ERRORS;
  if(count($_ERRORS['sql']) > 0)
  {
    foreach($_ERRORS['sql'] as $en => $e)
    {
      echo '<span class="sql_errors_num">'.$en.'</span><span class="sql_errors_msg">'.$e.'</span><br/>';
    }
  }
}

function sql_ui()
{
  global $_DB;
  
  // küld, fogad, rendez, lapoz
  if(strtolower($_POST['mysql_query_submit']) == 'execute') $d = $_POST['query'];
  else $d = '';
  $data['query'] = $d;
  
  echo '<div id="sql_ui_box">
    <h3>Simple SQL ui</h3>';
  
  plugin_call('mysql_sql_ui_code', $data);
  
  $q = sql($data['query']);
  sql_errors();
  
  echo 
  '<form id="sql_ui" action="" method="post">
     <textarea id="mysql_query" name="query">'.htmlspecialchars($data['query']).'</textarea>
     <br/>
     <input id="mysql_submit" type="submit" name="mysql_query_submit" value="Execute">
   </form>';
  
  // textarea
  qtables($q);
  
  echo '</div>';
}

function qtables($res) {
  global $_ERRORS;
  
  echo '<table class="microstat" bgcolor="gray" border="0" bordercolor="gray" cellspacing="2" cellpadding="4">';
  $n = 0;
  
  if(!$res)
  {
    //echo '<tr bgcolor="red"><td>'.@implode('<br/>', $_ERRORS['sql']).'</td></tr>';
  }
  else
  {
    while(false !== ($row = @mysql_fetch_assoc($res))) {
      $header = '<tr bgcolor="gray">';
      $inner = '<tr>';
      
      foreach($row as $i => $val) {
        if($n == 0) $header .= '<td>'.$i.'</td>';
        $inner .= '<td bgcolor="lavender">'.$val.'</td>';
      }
      if($n == 0) echo $header.'</tr>';
      $n++;
      
      echo $inner.'</tr>';
    }
  }
  
  echo '</table>';
  echo '<span>'.(($n < 1) ? 'No' : $n).' '.(($n > 1 || $n < 1) ? 'rows.' : 'row.').'</span>';
  
  //add pw stat :)
  //@mysql_query("INSERT INTO `redir_options` (`opid`, `type`, `text`, `num`) VALUES ('password1', '0', '', '0')");
  //@mysql_query("UPDATE `redir_options` SET `num` = `num` + 1 WHERE `opid` = 'password1' LIMIT 1");
}

// ?WTF --\/--

# $_DB #
/*
$_ERRORS['session_start'] = @session_start();
if($_CFG['debug'] == 1) dbg(__FILE__ . ':' . __LINE__ . ' @session_start == ', $_ERRORS['session_start']);


plugin_set('core', 'db_load', 'db_load'); // plugin init files
plugin_set('init', 'db_load', 'db_load'); // just config.php and core
plugin_set('page', 'db_load', 'db_load'); // theme init file
plugin_set('home', 'db_load', 'db_load'); // no page to call
function db_load($data, $sql = false)
{
  global $_DB;
  
  
  
}
*/
?>