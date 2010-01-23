<?php
if(!defined('CORE')) { die('*'); }
global $_USERS, $_CFG;

if(PAGE == 'logout')
{
  user_logout();
}

$REQ = explode('/', PAGE . REQ);

dbg(__FILE__ . ':' . __LINE__ . ' $REQ ', $REQ);
dbg(__FILE__ . ':' . __LINE__ . ' $_SESSION ', $_SESSION);
dbg(__FILE__ . ':' . __LINE__ . ' $_USERS ', $_USERS);
dbg(__FILE__ . ':' . __LINE__ . ' $_POST ', $_POST);
//dbg(__FILE__ . ':' . __LINE__ . ' $_SERVER ', $_SERVER);
//$_USER = user_id($REQ[1]);

if($REQ[1] == 'ccfg')
{
  ccfg_form_submit($data, 'the_users', '_USERS'); // theme init file
}
// rendes submenut
$_CFG['theme_cfg']['menus']['Users config'] = DOMAIN . PAGE . '/ccfg';

head($data);
?>
<script type="text/javascript">
<!--

-->
</script>
<?php
if($REQ[1] == 'ccfg')
{
  ccfg_form($data, 'the_users', '_USERS');
}
else
{
  echo '<br/>' . __FILE__ . ':' . __LINE__;
}

foot($data);
?>