<?php
if(!defined('CORE')) { die('*'); }
global $_DB, $_CFG;

$REQ = explode('/', PAGE . REQ);

dbg(__FILE__ . ':' . __LINE__ . ' $REQ ', $REQ);
dbg(__FILE__ . ':' . __LINE__ . ' $_DB ', $_DB);
dbg(__FILE__ . ':' . __LINE__ . ' $_POST ', $_POST);
//dbg(__FILE__ . ':' . __LINE__ . ' $_SERVER ', $_SERVER);
//$_USER = user_id($REQ[1]);

if($REQ[1] == 'ccfg')
{
  ccfg_form_submit($data, 'the_db', '_DB');
}

// rendes submenut
$_CFG['theme_cfg']['menus']['DB config'] = 'http://'.$_SERVER['HTTP_HOST'].'/'.PAGE.'/ccfg';

head($data);
?>
<script type="text/javascript">
<!--

-->
</script>

<?php
if($REQ[1] == 'ccfg')
{
  ccfg_form($data, 'the_db', '_DB');
}
else
{
  sql_ui();
}

//echo '<br/>' . __FILE__ . ':' . __LINE__;

foot($data);
?>