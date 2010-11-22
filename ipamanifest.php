<?php
/*
 * ipamanifest.php, generate wireless app distribution mainifest
 * directly from IPA file.
 *
 * More information can be found on Apples developer site:
 * http://developer.apple.com/library/ios/#featuredarticles/FA_Wireless_Enterprise_App_Distribution/Introduction/Introduction.html
 *
 * Copyright (c) 2010 <mattias.wadman@gmail.com>
 *
 * MIT License:
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 */

require_once("cfpropertylist/CFPropertyList.php");

class IOSVersion {
  public $models;
  public $major;
  public $minor;

  function __construct($models, $major, $minor) {
    $this->models = $models;
    $this->major = $major;
    $this->minor = $minor;  
  }

  static public function from_agent($agent) {
    if(!preg_match('/\((?P<model>iPhone|iPad|iPod)(?: Simulator)?;.*OS (?P<major>\d+)_(?P<minor>\d+)(?:_\d+)?/', $agent, $m))
      return FALSE;

    return new IOSVersion(array($m["model"]), (int)$m["major"], (int)$m["minor"]);
  }

  static public function from_family_version($family, $version) {
    if(!preg_match('/^(?P<major>\d+)\.(?P<minor>\d+).*$/', $version, $m))
      return FALSE;

    if(!is_array($family))
      $family = array($family);
    $models = array();
    foreach($family as $f) {
      if($f == 1) {
	$models[] = "iPhone";
	$models[] = "iPod";
	$models[] = "iPad";
      } else if($f == 2) {
	$models[] = "iPad";
      }
    }

    return new IOSVersion($models, (int)$m["major"], (int)$m["minor"]);
  }

  public function less_than($other) {
    return
      $this->major < $other->major ||
      ($this->major == $other->major && $this->minor < $other->minor);
  }

  public function has_model($other) {
    foreach($other->models as $m)
      if(in_array($m, $this->models))
	return TRUE;

    return FALSE;
  }

  public function version_string() {
    return $this->major . "." . $this->minor;
  }
}

class IPAFile {
  private $zip;
  private $base_path;
  public $file_id;
  public $ipafile;
  public $info;
  public $id;
  public $display_name;
  public $version;
  public $icon_name;
  public $created;
  public $has_prerendered_icon;

  function __construct($file_id, $ipafile) {
    $this->zip = new ZipArchive();
    $this->zip->open($ipafile);
    $this->file_id = $file_id;

    // find Payload/Name.app prefix inside IPA
    for($i = 0; $i < $this->zip->numFiles; $i++) {
      $dirs = explode("/", $this->zip->getNameIndex($i));
      if(count($dirs) > 2) {
	$this->base_path = "{$dirs[0]}/{$dirs[1]}";
	break;
      }
    }

    $p = new CFPropertyList();
    $p->parse($this->read("Info.plist"));
    $this->info = $p->toArray();
    
    $s = $this->zip->statName($this->ipa_name($this->info["CFBundleExecutable"]));
    $this->created = $s["mtime"];
    $this->id = $this->info["CFBundleIdentifier"];
    $this->version = $this->info["CFBundleVersion"];
    $this->ios_version = IOSVersion::from_family_version($this->info["UIDeviceFamily"],
							 $this->info["MinimumOSVersion"]);
    $this->name = $this->info["CFBundleName"];
    $this->display_name = $this->info["CFBundleDisplayName"];
    $this->icon_name = $this->find_icon_name();
    $this->has_prerendered_icon =
      isset($this->info["UIPrerenderedIcon"]) &&
      $this->info["UIPrerenderedIcon"];
  }

  public function ipa_name($name) {
    return "{$this->base_path}/{$name}";
  }

  public function read($name) {
    return $this->zip->getFromName($this->ipa_name($name));
  }

  public function exists($name) {
    return $this->zip->statName($this->ipa_name($name)) != FALSE;
  }			 

  public function strip_image_name($name) {
    $pi = pathinfo($name);

    if(empty($pi["extension"]))
      $ext = "png";
    else
      $ext = $pi["extension"];

    if($pi["dirname"] == ".")
      $prefix = "";
    else
      $prefix = "{$pi["dirname"]}/";

    if($pi["extension"] == "")
      $name = $pi["basename"];
    else
      $name = substr($pi["basename"], 0, -(strlen($ext) + 1));

    if(substr($name, -3, 3) == "@2x")
      $name = substr($name, 0, -3);

    return "$prefix$name";
  }
  
  public function find_icon_name() {
    $icons = array();
    if(isset($this->info["CFBundleIconFiles"]))
      $icons = $this->info["CFBundleIconFiles"];
    $icons[] = $this->info["CFBundleIconFile"];
    $icons[] = "Icon";
    $icons[] = "icon";

    foreach($icons as $icon) {
      $stripped = $this->strip_image_name($icon);

      foreach(array("@2x.png", ".png") as $ext) {
	if($this->exists("$stripped$ext"))
	  return "$stripped$ext";
      }
    }

    return "";
  }
}

class AbstractIPAManifest {
  private $ipafile;

  function __construct($file_id, $ipafile) {
    $this->ipafile = $ipafile;
    $this->ipa = new IPAFile($file_id, $ipafile);
    $this->link_ipa = $this->link($this->ipa->file_id, "ipa");
    if(isset($this->ipa->icon_name))
      $this->link_icon = $this->link($this->ipa->file_id, "icon");
    $this->link_manifest = $this->link($this->ipa->file_id, "manifest");
    $this->link_display_image = $this->link($this->ipa->file_id, "display-image");
    if($this->ipa->exists("iTunesArtwork"))
      $this->link_full_size_image = $this->link($this->ipa->file_id, "full-size-image");
    if($this->ipa->exists("embedded.mobileprovision"))
      $this->link_mobileprovision = $this->link($this->ipa->file_id, "mobileprovision");
    $this->link_itms =
      "itms-services://" .
      "?action=download-manifest" .
      "&url=" . urlencode($this->link_manifest);
  }
 
  // override this method and return URLs that will end up in the dispatch method
  public function link($file_id, $action) {
  }

  public function dispatch($action) {
    if($action == "icon")
      return $this->icon_data();
    else if($action == "manifest")
      return $this->manifest_data();
    else if($action == "display-image")
      return $this->display_image_data();
    else if($action == "full-size-image")
      return $this->full_size_image_data();
  }

  public function icon_data() {
    header("Content-Type: image/png");
    echo $this->ipa->read($this->ipa->icon_name);
  }
  
  public function display_image_data() {
    return $this->icon_data();
  }

  public function full_size_image_data() {
    header("Content-Type: image/png");
    echo $this->ipa->read("iTunesArtwork");
  }

  public function manifest_data() {
    $plist = new CFPropertyList();
    $dict = new CFDictionary();
    $plist->add($dict);
    $items = new CFArray();
    $dict->add("items", $items);
    $download = new CFDictionary();
    $items->add($download);
    $assets = new CFArray();
    $metadata = new CFDictionary();
    $download->add("assets", $assets);
    $download->add("metadata", $metadata);
    
    $software_package = new CFDictionary();
    $assets->add($software_package);
    $software_package->add("kind", new CFString("software-package"));
    $software_package->add("url", new CFString($this->link_ipa));

    $display_image = new CFDictionary();
    $assets->add($display_image);
    $display_image->add("kind", new CFString("display-image"));
    $display_image->add("url", new CFString($this->link_display_image));
    if($this->has_prerendered_icon)
      $display_image->add("needs-shine", False);

    if($this->ipa->exists("iTunesArtwork")) {
      $full_size_image = new CFDictionary();
      $assets->add($full_size_image);
      $full_size_image->add("kind", new CFString("full-size-image"));
      $full_size_image->add("url", new CFString($this->link_full_size_image));
      if($this->has_prerendered_icon)
	$full_size_image->add("needs-shine", False);
    }

    $metadata->add("bundle-identifier", new CFString($this->ipa->id));
    $metadata->add("bundle-version", new CFString($this->ipa->version));
    $metadata->add("kind", new CFString("software"));
    // iTunes?
    //$metadata->add("subtitle", new CFString("subtitle"));
    $metadata->add("title", new CFString($this->ipa->display_name));

    header("Content-Type: text/xml");
    echo $plist->toXML(); 
  }
}

?>
