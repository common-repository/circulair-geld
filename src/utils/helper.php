<?php

//
// This file contains small helper functions
//

if (!function_exists('console_log')) {
  function console_log($output) {   
    if (defined('WP_DEBUG') && WP_DEBUG === true) {
      echo "<script>console.log(" . json_encode($output, JSON_HEX_TAG) . ");</script>";
   }   
  }
}

if (!function_exists('write_log')) {
  function write_log($output) {   
    if (defined('WP_DEBUG') && WP_DEBUG === true) {
      if (is_array($output) || is_object($output)) {
        error_log(print_r($output, true));
      } else {
          error_log($output);
      }
    }   
  }
}

?>