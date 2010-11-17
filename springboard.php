<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
  <head>
    <title>Applications</title>
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
	height: 100px;
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
    </style>
  </head>
  <body>
  <div class="gradient">
    <? foreach($manifests as $m) { ?>
      <div class="app">
	<div class="icon">
	  <a href="<?=$m->link_itms?>">
	    <div class="icon-shadow"></div>
	    <div class="<?=$m->ipa->has_prerendered_icon ? "" : "icon-glossy"?>"></div>
	    <img class="icon-rounded" src="<?=$m->link_icon?>">
	  </a>
	</div>
	<span class="app-display-name"><a href="<?=$m->link_itms?>"><?=htmlspecialchars($m->ipa->display_name)?></a></span><br>
	<span class="app-created"><?=date("Y-m-d", $m->ipa->created)?></span><br>
      </div>
    <? } ?>
    <div style="clear: both"></div>
  </div>
  </body>
</html>
