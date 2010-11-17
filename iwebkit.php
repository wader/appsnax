<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta content="yes" name="apple-mobile-web-app-capable" />
  <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
  <meta content="minimum-scale=1.0, width=device-width, maximum-scale=0.6667, user-scalable=no" name="viewport" />
  <link href="iwebkit/css/style.css" rel="stylesheet" media="screen" type="text/css" />
  <link href="css/appicon.css" rel="stylesheet" media="screen" type="text/css" />
  <script src="iwebkit/javascript/functions.js" type="text/javascript"></script>
  <title>Applications</title>
  <style type="text/css">
    /* ugly iwebkit overrides */
    .list ul img {
      width: 57px !important;
      height: 57px !important;
    }
    .list li a {
      padding: 5px 10px 0px 20px !important;
    }
    .list .withimage {
      height: 70px !important;
    }
    .list .withimage .name {
      margin: 5px 0px 0px 70px !important;
      position: absolute;
    }
    .list .withimage .comment {
      margin: 25px 0px 0px 70px !important;
      position: absolute;
    }
  </style>
</head>
<body class="list">
  <div id="topbar">
    <div id="leftnav"></div>
    <div id="rightnav"></div>
    <div id="title">Applications</div>
  </div>
  <div id="content">
  <? foreach($manifests as $m) { ?>
    <ul>
      <li class="withimage">
	<a class="noeffect" href="<?=$m->link_itms?>">
	  <span class="icon">
	    <div class="icon-shadow"></div>
	    <div class="<?=$m->ipa->has_prerendered_icon ? "" : "icon-glossy"?>"></div>
	    <img class="icon-rounded" src="<?=$m->link_icon?>">
	  </span>
	  <span class="name"><?=htmlspecialchars($m->ipa->display_name)?></span>
	  <span class="comment">
	    Version <?=$m->ipa->version?><br>
	    <?=date("Y-m-d", $m->ipa->created)?><br>
	  </span>
	</a>
      </li>
    </ul>
  <? } ?>
  </div>
  <div id="footer"></div>
</body>
</html>
