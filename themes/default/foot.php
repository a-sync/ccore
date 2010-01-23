<?php if(!defined('CORE')) { die('*'); } ?>
    </div><!--/content-->


    <div id="foot"><!--foot-->
      <?php echo $_CFG['theme_cfg']['foot'].' ('.$_CFG['version'].')'; ?>
    <?php if($_CFG['debug']) { ?>
      <pre id="debug" ondblclick="toggle_onload('debug');fade_elem_opacity(0, 0, 10, 40, 'debug');" style="text-align: left;"><?php echo $_ERRORS['dbg']; ?></pre>
    <?php } ?>
    </div><!--/foot-->


  </div><!--/container-->
</div><!--/container_frame-->

</body>
</html>