<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:svg="http://www.w3.org/2000/svg" xml:lang="en" lang="en">
<head>
  <meta content="text/xhtml; charset=utf-8" http-equiv="Content-Type" />
  <link href="css/appicon.css" rel="stylesheet" media="screen" type="text/css" />
  <title>Applications</title>
  <style>
  .content {
    width: 80%; 
    padding: 0;
    margin: 0 auto;
    font-family: "Helvetica Neue";
  }
  .itunes-instructions {
    font-size: 0.8em;
    background-color: #eee;
    border: solid 1px #ccc;
    padding: 0px 0px 10px 10px;
    margin: 10px;
  }
  .app {
    height: auto;
    float: left;
    width: 50%;
  }
  .icon {
    margin: 10px;
    float: left;
  }
  .info {
    margin: 10px;
    float: left;
  }
  .name {
    font-weight: bold;
    font-size: 1.2em;
  }
  .dash {
    font-weight: bold;
    color: #666;
  }
  .download {
    font-size: 0.8em;
  }
  .version {
    font-weight: bold;
    font-size: 0.8em;
    color: #666;
  }
  </style>
</head>
<body>
<div class="content">
  <div class="itunes-instructions">
  <p>
    If you have a device with iOS version 4.0 or higher the easiest way to install
    Ad Hoc applications is to visit this web site directly from the device.
    Otherwise follow the instructions below.
  </p>
  <ul>
    <li>Download the IPA (Archived iPhone Application) and mobileprovision file.
    <li>Plug in your device and make sure it appears in iTunes.
    <li>Drag-and-drop the mobileprovision file onto the Apps or Applications label in iTunes.
    <li>Drag-and-drop the IPA file onto the Apps or Applications label in iTunes.
    <li>Make sure "Sync Apps" is checked on your device in iTunes.
    <li>Press sync and when done the app should appear on your device.
  </ul>
  You can also download
  <a href="https://www.apple.com/support/iphone/enterprise/">iPhone Configuration Utility</a> from Apple which will help you to install apps and a lot more.
  </div>
  <?php foreach($manifests as $m) { ?>
  <div class="app">
    <div class="icon">
      <div class="icon-shadow"></div>
      <div class="icon-background"></div>
      <?php if(isset($m->link_full_size_image)) { ?>
      <img class="icon-rounded" src="<?=$m->link_full_size_image?>"> 
      <?php } ?>
      <div class="<?=$m->ipa->has_prerendered_icon ? "" : "icon-glossy"?>"></div>
    </div>
    <div class="info">
      <span class="name"><?=htmlspecialchars($m->ipa->display_name)?></span>
      <span class="dash">-</span>
      <span class="download">
	<a href="<?=$m->link_ipa?>">IPA</a> /
	<a href="<?=$m->link_mobileprovision?>">mobileprovision</a>
      </span><br>
      <span class="version">
	Version <?=$m->ipa->version?>
	for <?=implode_natural(", ", " and ", $m->ipa->ios_version->models)?><br>
	<?=date("Y-m-d", $m->ipa->created)?>
      </span>
    </div>
    <div style="clear: both"></div>
  </div>
  <?php } ?>
</div>
</body>
</html>
