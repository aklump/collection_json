<?php
/**
 * @file
 * Provides functions for build scripts
 *
 * @ingroup web_package
 * @{
 */
function js_replace_name_version(&$text, $package_name, $new_version) {
  $regex = '/(.*?)( JQuery JavaScript Plugin v)([^\s]+)/i';
  $text = preg_replace($regex, ' * ' . $package_name . '${2}' . $new_version,  $text);
}

function js_replace_description(&$text, $description) {
  $text = " * $description";
}

function js_replace_date(&$text, $date) {
  $text = " * Date: $date";
}

function js_replace_homepage(&$text, $homepage) {
  $text = " * $homepage";
}