<?php
/*
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

function base_url() {
  return
    "http" . ($_SERVER["HTTPS"] != "" ? "s" : "") . 
    "://{$_SERVER["SERVER_NAME"]}" .
    (($_SERVER["HTTPS"] != "" && $_SERVER["SERVER_PORT"] != "443") ||
     ($_SERVER["HTTPS"] == "" && $_SERVER["SERVER_PORT"] != "80") ?
     ":" . $_SERVER["SERVER_PORT"] : "");
}

// used to convert array(a,b,c) to "a, b and c"
function implode_natural($sep, $last, $items) {
  if(count($items) < 3)
    return implode($last, $items);

  return
    implode($sep, array_slice($items, 0, -1)) .
    $last . $items[count($items) - 1];
}

?>
