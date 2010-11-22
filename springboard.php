<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
  <head>
    <title>Applications</title>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
    <meta content="yes" name="apple-mobile-web-app-capable" />
    <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
    <link href="css/appicon.css" rel="stylesheet" media="screen" type="text/css" />
    <style>
      body {
	padding: 0;
	margin: 0;
	background-color: #777;
	font-family: "Helvetica Neue";
      }
      a {
	color: #fff;
	text-decoration: none;
      }
      .gradient {
	padding: 10px;
	font-weight: bold;
	background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(#ddd), to(#777));
      }
      .icon {
	margin: 0 auto;
      }
      .app {
	margin: 0 auto;
	text-align: center;
	width: 32%;
	float: left;
      }
      .app-display-name {
	font-size: 1em;  
	text-shadow: #000 0px 1px 2px;
      }
      .app-created {
	font-size: 0.3em;
	color: #444;
      }
      .app-version {
	font-size: 0.1em;
	color: #eee;
      }
      .warning {
	background-color: #f99;
	height: auto;
	padding: 5px;
	border: solid 1px #999;
	margin: 10px;
      }
      .warning-requires {
	color: #922;
	font-size: 0.3em;
      }
    </style>
  </head>
  <body>
    <div class="gradient">
    <?php if(isset($warning)) { ?>
      <div class="warning"><?=$warning?></div>
    <?php } ?>
    <? foreach($manifests as $m) { ?>
      <div class="app">
	<a href="<?=$m->link_itms?>">
	  <div class="icon">
	      <div class="icon-shadow"></div>
	      <div class="icon-background"></div>
	      <div class="<?=$m->ipa->has_prerendered_icon ? "" : "icon-glossy"?>"></div>
	      <?php if(isset($m->link_icon)) { ?>
	      <img class="icon-rounded" src="<?=$m->link_icon?>">
	      <?php } ?>
	  </div>
	</a>
	<span class="app-display-name"><a href="<?=$m->link_itms?>"><?=htmlspecialchars($m->ipa->display_name)?></a></span><br>
	<span class="app-created"><?=date("Y-m-d", $m->ipa->created)?></span><br>
	<?php if(!$m->ipa->ios_version->has_model($ios)) { ?>
	<span class="warning-requires">
	  Requires <?=implode_natural(", ", " or ", $m->ipa->ios_version->models)?>
	</span>
	<?php } else if($ios->less_than($m->ipa->ios_version)) { ?>
	<span class="warning-requires">
	  Requires iOS <?=$m->ipa->ios_version->version_string()?>
	</span>
	<?php } ?>
      </div>
    <? } ?>
    <div style="clear: both"></div>
  </div>
  </body>
</html>
