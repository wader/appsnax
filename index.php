<?php
/*
 * Example usage of ipamanifest
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

define("IPAPATH", "ipas");

require_once("ipamanifest.php");
require_once("misc.php");

class IPAManifest extends AbstractIPAManifest {
  public function link($file_id, $action) {
    // you can direct the ipa action directly to the ipa-file if you want
    /*
    if($action == "ipa") {
      $path = dirname($_SERVER["SCRIPT_NAME"]);
      if($path == "/")
	$path = "";
      $file = urlencode($file_id);
      return base_url() . "$path/" . IPAPATH . "/$file";
    } 
    */

    return base_url() .
      "{$_SERVER["SCRIPT_NAME"]}" . 
      "?file=" . urlencode($file_id) .
      "&action=" . urlencode($action);
  }
}

function manifest_created_cmp($a, $b) {
  return $b->ipa->created - $a->ipa->created;
}

function collect_manifests($path) {
  $manifests = array();
  foreach(glob("$path/*.ipa") as $ipafile) {
    $file_id = basename($ipafile);
    $manifests[$file_id] = new IPAManifest($file_id, $ipafile);
  }

  uasort($manifests, "manifest_created_cmp");

  return $manifests;
}

//$ios = new IOSVersion(array("iPhone"), 4, 0);
//$ios = new IOSVersion(array("iPhone"), 2, 0);
//$ios = new IOSVersion(array("iPad"), 4, 0);
//$ios = FALSE; 
$ios = IOSVersion::from_agent($_SERVER["HTTP_USER_AGENT"]);
$manifests = collect_manifests(IPAPATH);

$file = isset($_GET["file"]) ? $_GET["file"] : "";
$action = isset($_GET["action"]) ? $_GET["action"] : "";

if(isset($manifests[$file])) {
  return $manifests[$file]->dispatch($action);
}

//require_once("springboard.php");
require_once("iwebkit.php");

?>
