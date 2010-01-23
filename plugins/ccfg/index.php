<?php
if(!defined('CORE')) { die('*'); }

ccfg_form_submit($data);

head($data);
?>

<script type="text/javascript">
<!--

-->
</script>

<?php

ccfg_form($data);

// little extra info about the $_CFG stages
foreach($_CCFG as $n => $config)
{
  if('main' != $n) echo '<h4 class="ccfg_call" onclick="toggle_onload(\'toggle_'.$n.'\');">'.$n.'</h4><pre style="display: none;" id="toggle_'.$n.'" class="ccfg_debug" ondblclick="" style="text-align: left;">'.var_export($config, true).'</pre>';
}

foot($data);
?>