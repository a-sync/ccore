<?php if(!defined('CORE')) { die('*'); }
global $_PAGES;

$_PAGES['ccfg'] = 'ccfg';

// javascripts to call from the theme
$_CFG['javascripts'][] = 'style/sliding_toggler-1.06.js';

plugin_set('js_ccfg_toggle_id', 'js_slide_id');
function js_slide_id($data)
{
  //js_ccfg_toggle_id(id, open);
  ?>
  // JavaScript for sliding_toggler-1.06.js
    if(_(id).style.display != 'none')
    {
      if(open != true) toggle_onload(id);
    }
    else
    {
      toggle_onload(id);
    }

    return false;
  <?php
}

?>