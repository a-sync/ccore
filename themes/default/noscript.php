<?php if(!defined('CORE')) { die('*'); }
head($data);
?>
<div id="noscript" class="noscript" style="" onclick="toggle_onload('noscript');fade_elem_opacity(0, 100, 10, 40, 'noscript');">
 <div>
   <a href="#">JavaScript</a> is essential to the site's theme.
 </div>
</div>

<script type="text/javascript">
  <!--
  //if(window.attachEvent) window.attachEvent('onload', toggle_onload);
  //else window.addEventListener('load', toggle_onload, false);
  // toggle_onload


  -->
</script>
JavaScript is essential to the site's theme.
<br/><br/>
Turning on javascript help comes here (For popular browsers)
<!--
Legacy browsers / browser supports are not welcome.
-->
<br/><br/>
Check your browsers settings.
<?php
foot($data);
?>