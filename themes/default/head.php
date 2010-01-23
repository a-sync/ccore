<?php if(!defined('CORE')) { die('*'); }
login_form(true); // login form handler
 ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="content-type" content="text/html; charset=utf-8" />

  <meta name="keywords" content="<?php echo $_CFG['keywords']; ?>" />
  <meta name="description" content="<?php echo $_CFG['description']; ?>" />

  <!-- robots.txt -->
  <meta name="googlebot" content="noarchive, nosnippet, noindex, nofollow" />
  <meta name="msnbot" content="noodp" />

  <meta name="language" content="english" />

  <meta name="author" content="" />
  <meta name="copyright" content="" />
<!--
  <link href="<?php echo DOMAIN . THEME; ?>favicon.ico" rel="shortcut icon" type="image/x-icon" />
  <link href="<?php echo DOMAIN . THEME; ?>favicon.ico" rel="icon" type="image/x-icon" />
-->
  <title><?php echo strip_tags($_CFG['theme_cfg']['title']); ?></title>

  <link rel="stylesheet" type="text/css" href="<?php echo DOMAIN . THEME; ?>style/default.css" title="default" />
  <!--[if lte IE 6]>
    <link rel="stylesheet" type="text/css" href="<?php echo DOMAIN . THEME; ?>style/default_lte_ie6.css" />
  <![endif]-->
  <!--[if lte IE 7]>
    <link rel="stylesheet" type="text/css" href="<?php echo DOMAIN . THEME; ?>style/default_lte_ie7.css" />
  <![endif]-->
  <!--chrome frame tag ie8 behave like ie7 tag-->

  <?php
    if(is_array($_CFG['javascripts'])) {
      foreach($_CFG['javascripts'] as $n => $f) {
        echo '<script type="text/javascript" src="' . DOMAIN . THEME.$f . '"></script>';
      }
    }
  ?>
</head>
<body>

<div id="container_frame"><!--container_frame-->
  <div id="container"><!--container-->


    <div id="head"><!--head-->
      <div id="logo"><?php echo $_CFG['theme_cfg']['title']; ?></div>
    </div><!--/head-->
    
    <?php if(is_array($_CFG['theme_cfg']['menus'])) { // todo: templater function with recursive menu sampling (ul - li) ?>
      <div id="menu"><!--menu-->
        <ul>
      <?php foreach($_CFG['theme_cfg']['menus'] as $name => $link) {?>
          <li><a href="<?php echo htmlspecialchars($link); ?>"><?php echo htmlspecialchars($name); ?></a></li>
      <?php } ?>
        </ul>
      </div><!--/menu-->
    <?php } ?>
    
    <?php if(is_array($_ERRORS['warnings'])) { ?>
      <div class="errors"><?php echo implode('<br />', $_ERRORS['warnings']); ?></div>
    <?php } ?>

    <div id="content"<?php if(isset($data['class'])) { echo ' class="'.$data['class'].'"'; } ?>><!--content-->
      <!--login_form-->
      <?php login_form(); // login form; ?>
      <!--/login_form-->
      
      <?php if(is_array($_SESSION['messages'])) { ?>
        <div class="messages"><?php echo implode('<br />', $_SESSION['messages']); ?></div>
      <?php } ?>

      <?php if(PAGE != 'noscript') { ?>
      <noscript class="noscript">
          <a href="<?php echo DOMAIN; ?>/noscript">JavaScript</a> is essential to the site's theme.
          <?php 
            if(PAGE == 'ccfg')
            {
              echo '<br/><span>* You cant edit array keys in table mode without javascript!</span>';
            }
          ?>
      </noscript>
      <?php } ?>