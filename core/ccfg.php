<?php
// configokat funkciohivassal menteni
if(!defined('CORE')) { die('*'); }
// before page / home ccfg_reload sets the $_CCFG var with the saved config if present
$_CCFG = false;

plugin_set('core', 'ccfg_load', 'ccfg'); // just config.php and core
plugin_set('init', 'ccfg_load', 'ccfg'); // plugin init files
plugin_set('page', 'ccfg_load', 'ccfg'); // theme init file
plugin_set('home', 'ccfg_load', 'ccfg'); // no page to call
function ccfg_load(&$data, $name = 'the_config')
{
  global $_CFG, $_CCFG, $_ERRORS, $_the_config;
  
  if(isset($data['t']) == true || isset($data['_ERRORS'][404]) == true) // 3. run
  {
    // page - home
    $_CCFG['page'] = $_CFG;
    //dbg('$_CCFG', $_CCFG);
    
    // last check
    ccfg_reload($data, $name, '_CFG');
  }
  elseif(isset($data['_ERRORS']) == true) // 2.
  {
    // init
    $_CCFG['init'] = $_CFG;
  }
  elseif(isset($data['n']) == true) // 1.
  {
    // core
    $_CCFG['core'] = $_CFG;
  }
}

function ccfg_reload(&$data, $name = 'the_config', $global = '_CFG')
{
  global $$global;
  
  $file = CORE . 'configs/' . $name . '.php';
  //touch($file);
  if(is_file($file))
  {
    @include($file);
    if(isset($the_array) && is_array($the_array))
    {
      $$global = $the_array;
      unset($the_array);
    }
  }
  //touch($file);
}

function ccfg_form_submit(&$data = false, $name = 'the_config', $global = '_CFG')
{
  global $$global;
  
  if(isset($_POST['ccfg_submit']))
  {
//dbg('ccfg_form $_POST', $_POST);

    if($_POST['ccfg_submit'] == 'Save')
    {
      $the_var = var_export($_POST[$name], true);
    }
    else
    {
      $the_var = $_POST['the_array'];
    }

    //$the_var = php_strip_whitespace('< ?php if(!defined("CORE")) { die("*"); } $the_array = '.$_POST['the_array'].'; ? >');
    $the_var = '<?php if(!defined("CORE")) { die("*"); } $the_array = '.trim($the_var).'; ?>';
    
    //dbg('ccfg_form _POST', $_POST);
    //dbg('ccfg_form $the_var', $the_var);
    
    $file = CORE . 'configs/' . $name . '.php';
    if(!is_file($file)) { touch($file); }
    
    $_ERRORS['file_put_contents('.$file.', $the_var)'] = file_put_contents($file, $the_var);
    dbg('$_ERRORS[file_put_contents('.$file.', $the_var)] == '.$_ERRORS['file_put_contents('.$file.', $the_var)'].' $the_var == ', $the_var);
    //dbg('ccfg_form_submit', $the_var);
    
    ccfg_reload($data, $name, $global);
  }
}

function ccfg_form(&$data = false, $name = false, $global = '_CFG')
{
  global $$global, $_CCFG;
  if($name == false) { $name = 'the_config'; }
  if($_CFG['debug'] == 1) { dbg(__FILE__ . ' ccfg_form:: .'.$name, $$global); } 
  
?>
<script type="text/javascript">
<!--
  //ccfg_change_key(this); // id = input
  //ccfg_change_key(this, true); // id = div
// rekurziv funkcio
  function ccfg_change_key(key, array)
  {
    if(key == false) document.getElementById('ccfg_form_save_raw').style.visibility = 'visible';
    if(!key) return false;
    document.getElementById('ccfg_form_save').style.visibility = 'visible';
    //key_the_config--version
    var val = document.getElementById(key.id.substr(4)); // the_config--version
    if(!val) return false;

    ccfg_plus(false, false, false, false, true);
    
    if(!array)
    {
      var val_start = key.id.substring(4, key.id.lastIndexOf('--') + 2);
      var val_start_a = val_start.split('--');
      var val_name = val_start_a.shift();
      var val_id = val_start + key.value;
      
      key.id = 'key_' + val_id;
      val.id = val_id;
      val.name = val_name + '[' + val_start_a.join('][') + key.value + ']';
    }
    else
    {
      var val_start = key.id.substring(4, key.id.lastIndexOf('--') + 2);
      var val_start_a = val_start.split('--');
      var val_name = val_start_a.shift();
      
      var vals = val.getElementsByTagName('input');

      for(var i in vals)
      {
        if(vals[i].id)
        {
          if(vals[i].id.indexOf(val_start) == 0 && vals[i].id.indexOf('key_') != 0)
          {
            var val_end = vals[i].id.substr(vals[i].id.substr(val_start.length).indexOf('--') + val_start.length);
            var val_id = val_start + key.value + val_end;
            
            document.getElementById('key_' + vals[i].id).id = 'key_' + val_id; // key.id
            vals[i].id = val_id;
            vals[i].name = val_name + '[' + val_id.substr(val_name.length + 2).split('--').join('][') + ']';

          }
        }
      }
      
      val.id = val_start + key.value;
      key.id = 'key_' + val_start + key.value;
    }
    
  }
  
  function ccfg_toggle_id(id, open)
  {
    <?php
      plugin_call('js_ccfg_toggle_id', $data);
    ?>
    var object = document.getElementById(id);
    if(!object) return false;
    else
    {
      if(object.style.display == 'none' || open == true)
      {
        object.style.display = '';
      }
      else
      {
        object.style.display = 'none';
      }
    }

    return false;
  }
   
  function ccfg_form_submit(e, id)
  {
    var inputs = document.getElementById(id).getElementsByTagName('input');
    for(var i in inputs)
    {
      if(inputs[i].id && inputs[i].id.indexOf('key_') == 0)
      {
        var pair = inputs[i].id.substr(4);
        pair = document.getElementById(pair);
        
        if((inputs[i].value == '' || inputs[i].value == 0) && (pair.value == '' || !pair.value))
        {
          inputs[i].style.display = 'none';
          inputs[i].name = '';
          
          pair.style.display = 'none';
          pair.name = '';
          
          inputs[i].innerHTML = '';
          pair.innerHTML = '';
        }
      }
    }
    
    return true;
  }
  function ccfg_form_change_save(e)
  {
    document.getElementById('ccfg_form_save').style.visibility = 'visible';
  }
  function ccfg_form_reset_save(e, id)
  {
    document.getElementById('ccfg_form_save').style.visibility = 'hidden';
    return true;
  }
  function ccfg_form_save(e)
  {
    document.getElementById('ccfg_form_save_raw').style.visibility = 'hidden';
  }
  function ccfg_form_save_raw(e)
  {
    document.getElementById('ccfg_form_save').style.visibility = 'hidden';
  }
  function ccfg_form_reset_save_raw(e, id)
  {
    document.getElementById('ccfg_form_save_raw').style.visibility = 'hidden';
  }
  
  var brs = [];
  var save_pls = false;
  var save_text = 'Please save before adding any more variables.';
  function ccfg_plus(e, name_k, key_id, array, save)
  {
    if(save == true)
    {
      document.getElementById('ccfg_form_save').style.visibility = 'visible';
      save_pls = true;
      return false;
    }
    else if(save_pls === true)
    {
    <?php
      plugin_call('js_var_save', $data);
    ?>
      alert(save_text);
      return false;
    }
    
    ccfg_toggle_id(key_id, true);
    var object = document.getElementById(key_id);
    if(!object) return false;
    
    var n = Math.ceil(Math.random()*99999+10000);
    var m = Math.ceil(Math.random()*99999+10000);
    var name = name_k.split('--');
    var key = name.shift();
    name = key + '[' + name.join('][') + ']';

    if(!array)
    {
      object.innerHTML += '<br/>'
                       + '<input onchange="ccfg_change_key(this);" id="key_'+name_k+'--'+n+'" class="ccfg_key" type="text" />:'
                       //+ '<input onchange="ccfg_form_change_save(event);" class="ccfg_val" type="text" id="'+name_k+'--'+n+'" name="'+name+'['+n+']" />';
                       + '<input onchange="ccfg_form_change_save(event);" class="ccfg_val" type="text" id="'+name_k+'--'+n+'" name="" />';
    }
    else
    {
      object.innerHTML += '<br/>'
                       + '<input onchange="ccfg_change_key(this, true);" id="key_'+name_k+'--'+n+'" class="ccfg_array_key" type="text" />'
                       + '<a class="ccfg_array" href="#" onclick="return ccfg_toggle_id(\''+name_k+'--'+n+'\');">=&gt;</a>&nbsp;'
                       + '<div id="'+name_k+'--'+n+'" style="" class="ccfg_array_box">'
                       + '<input onchange="ccfg_change_key(this);" id="key_'+name_k+'--'+n+'--" class="ccfg_key" type="text" />:'
                       + '<input onchange="ccfg_form_change_save(event);" class="ccfg_val" type="text" id="'+name_k+'--'+n+'--" name="'+name+'['+n+'][]" />';
                       + '</div>';
      brs[key_id] = true;
    }
    
    return false;
  }
-->
</script>
<?php 
  echo '<form id="ccfg_form_'.$name.'" class="ccfg_form" action="" method="post" onsubmit="return ccfg_form_submit(event, \'ccfg_form_'.$name.'\');" autocomplete="off">';

  if(!is_array($$global))
  {
    echo $global.' is not an array.';
  }
  else
  {
    if(count($$global) < 1)
    {
      $$global = array('' => '');
    }
    echo '<div id="'.$name.'">'
        .'<h3>'.((isset($data['ccfg_form_array_title'])) ? $data['ccfg_form_array_title'] : $name.'').'</h3>';
    
    $data['ccfg_form_array'] = $$global;
    ccfg_form_array($data, $name);
    echo '</div>';
    echo '<input id="ccfg_form_save" type="submit" style="visibility: hidden;" onclick="return ccfg_form_save(event);" class="ccfg_submit" value="Save" name="ccfg_submit" /> <input onclick="return ccfg_form_reset_save(event, \'ccfg_form_'.$name.'\');" class="ccfg_reset" type="reset" value="Reset" />';
    
    echo '<h3>'.((isset($data['ccfg_form_array_title'])) ? $data['ccfg_form_array_raw_title'] : $name.' (raw)').'</h3>';
    
    echo '<textarea id="the_array" onchange="ccfg_change_key(false);" class="ccfg_textarea" name="the_array">'.var_export($data['ccfg_form_array'], true).'</textarea>'
         .'<br/>'
         .'<input id="ccfg_form_save_raw" type="submit" style="visibility: hidden;" onclick="return ccfg_form_save_raw(event);" class="ccfg_submit" value="Save raw" name="ccfg_submit" /> <input onclick="return ccfg_form_reset_save_raw(event, \'ccfg_form_'.$name.'\');" class="ccfg_reset" type="reset" value="Reset" />';  
  }
  
  echo '</form>';

}

function ccfg_form_array($data = array(), $name = 'the_config')
{
  global $_CFG, $_CCFG;
  
//dbg('ccfg_form_array name $data '.$name, $data);
  if(is_array($data['ccfg_form_array']))
  {
    $the_array = $data['ccfg_form_array'];

    $br = '';
    
    if($name == 'the_config' || 1 == 1)
    {
      $key_id = str_replace(array('[', ']'), array('--', ''), $name);
      echo '<div class="ccfg_add">'
          .'<a href="#" onclick="return ccfg_plus(event, \''.$key_id.'\', \''.$key_id.'\');"> + </a>'
          .'|'
          .'<a href="#" onclick="return ccfg_plus(event, \''.$key_id.'\', \''.$key_id.'\', true);"> &gt; </a>'
          .'</div>';
    }
    
    foreach($the_array as $key => $val)
    {
      $key_name = false;
      $key_id = false;
      
      $name_k = $name.'['.htmlspecialchars($key).']';
      
      if(is_array($val))
      {
        $data['ccfg_form_array'] = $val;
        if($key_name == false)
        {
          $key_name = true;
          $key_id = str_replace(array('[', ']'), array('--', ''), $name_k);
          
          echo $br.'<input onclick="return ccfg_toggle_id(\''.$key_id.'\', true);" onchange="ccfg_change_key(this, true);" id="key_'.$key_id.'" class="ccfg_array_key" type="text" value="'.htmlspecialchars($key).'" />';
          
          echo '<a class="ccfg_array" href="#" onclick="return ccfg_toggle_id(\''.$key_id.'\');">=&gt;</a>&nbsp;'
                .'<span class="ccfg_add">'
                .'<a href="#" onclick="return ccfg_plus(event, \''.$key_id.'\', \''.$key_id.'\');"> + </a>'
                .'|'
                .'<a href="#" onclick="return ccfg_plus(event, \''.$key_id.'\', \''.$key_id.'\', true);"> &gt; </a>'
                .'</span>';
        }
        
        echo '<div id="'.$key_id.'" class="ccfg_array_box">';// style="display: none;"
        ccfg_form_array($data, $name_k);
        echo '</div>';
      }
      else
      {
        $key_id = str_replace(array('[', ']'), array('--', ''), $name_k);
        
        echo $br.'<input onchange="ccfg_change_key(this);" id="key_'.$key_id.'" class="ccfg_key" type="text" value="'.htmlspecialchars($key).'" />:'
            .'<input onchange="ccfg_form_change_save(event);" class="ccfg_val" type="text" id="'.$key_id.'" name="'.$name_k.'" value="'.htmlspecialchars($val).'" />'
            .'';
      }
      $br = '<br/>';
    }
  }
  
  // echo here
}

?>