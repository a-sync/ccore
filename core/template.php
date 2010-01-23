<?php if(!defined('CORE')) { die('*'); }
//plugin amit beépítettünk az alap scriptbe

// ha nincs OUTPUT, akkor head es foot hivasa uresen
// 

function index($data)
{
  if(defined('OUTPUT')) return false;

  global $_CFG, $_ERRORS;
  
  include THEME . 'index.php'; // home can be routed here (note: also the right place to route static files)

}

function head($data = false)
{
  if(defined('HEAD')) return false;
  
  define('OUTPUT', 1); // do this if something is sent to the output buffer
  define('HEAD', 1); // HEAD CALLED
  
  global $_CFG, $_ERRORS;
  
  include THEME . 'head.php';
}

function foot($data = false)
{
  if(!defined('HEAD') || defined('FOOT')) return false;
  define('FOOT', 1); // HEAD CALLED
  
  global $_CFG, $_ERRORS;
  
  include THEME . 'foot.php';
}

// header küldők
function location($str)
{
  if(!is_string($str)) die('Invalid location.'); // ppl are counting on the exit call
  //header("Status: 300");
  header("Location: {$str}");
  exit;
}

/*
function redir($location = false) {
  header('Expires: Sat, 22 Aug 1987 06:06:06 GMT');
  header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
  header('Cache-Control: no-store, no-cache, must-revalidate');
  header('Cache-Control: post-check=0, pre-check=0');
  header('Pragma: no-cache');

  if($location != false) {
    header("Status: 200");
    header("Location: " . $location);
    exit;
  }

  return true;
}
*/

// form generáló
function formmaker($name, $elements, $action = '')
{
  if(is_string($name) && is_array($elements) && is_string($action))
  {// array to form maker funkcio
    echo '<form class="'.$name.'_form" action="" method="post">
            <ul class="login_inputs">';
      
    foreach($elements as $n => $prop)
    {
      // acceskeys (SHIFT + ALT + n)  // to emulate focus / active mouse events // submit gets onclick
      if(isset($prop['accesskey'])) $acceskey = 'accesskey="'.$prop['accesskey'].'" ';//ctype_alnum
      else $acceskey = '';
      
      if(isset($prop['tabindex'])) $tabindex = 'tabindex="'.$prop['tabindex'].'" ';//ctype_digit
      else $tabindex = '';
      
      if(isset($prop['maxlength']) && ctype_digit($prop['maxlength'])) $maxlength = 'maxlength="'.$prop['maxlength'].'" ';
      else $maxlength = '';
      
      if(isset($prop['input']))
      {
        if($prop['input'] == 'text' || $prop['input'] == 'password' || $prop['input'] == 'submit' || $prop['input'] == 'button')
        {
          echo '<li class="'.$name.'_'.$prop['input'].'">'.((is_int($n)) ? '' : '<label for="'.$name.'_'.$prop['name'].'" class="input_name">'.htmlspecialchars($n).'</label>').'<input id="'.$name.'_'.$prop['name'].'" name="'.$prop['name'].'" class="'.$name.'_'.$prop['input'].'" type="'.$prop['input'].'" value="'.htmlspecialchars($prop['value']).'" '.$acceskey.$tabindex.$maxlength.'/></li>';
        }
      }
      //else
    }
    
    echo '</ul>
        </form>';
  }
}


?>