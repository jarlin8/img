<?php

//ini_set ('display_errors', 1);
//error_reporting (E_ALL);

/*
// Function that translates the 32-bit unsigned long to a 4-byte string.
// Uses BCmath since php itself can't handle it. Also returns a group ID
// for the IP address - presently the high 13 bits - to calculate the index.

if (!function_exists ('bcmod')) {
  function bcmod ($x, $y) {
    // how many numbers to take at once? carefull not to exceed (int)
    $take = 5;
    $mod = '';

    do {
      $a = (int) $mod . substr ($x, 0, $take);
      $x = substr( $x, $take);
      $mod = $a % $y;
    }
    while (strlen ($x));

    return (int) $mod;
  }
}

if (!function_exists ('bcdiv')) {
  function bcdiv ($_ro, $_lo, $_scale = 0) {
    return round ($_ro/$_lo, $_scale);
  }
}
*/
function ip2bin ($ip) {
//  $lo = (int) bcmod ($ip, 65536);
//  $hi = (int) bcdiv ($ip, 65536, 0);

  $lo = $ip & 0xFFFF;
  $hi = $ip >> 16;
  return array (chr ($hi >> 8) . chr ($hi) . chr ($lo >> 8) . chr ($lo), $hi >> 3);
}

function process_csv ($file = '') {

  if ($file == '') $csv_file = 'IpToCountry.csv'; else $csv_file = $file;

  // Open the csv file
  $fp = fopen ($csv_file, 'r');

  if ($fp === false) {
    echo 'Error opening file ', $csv_file, "\n";
    return;
  }

  // Initialize
  $group    = null;
  $count    = 0;
  $data     = array();
  $index    = array();
  $t0       = time();
  $datapos  = 0;
  $testdata = '';

  // For each row...
  while (($row = fgetcsv ($fp, 255)) !== false)
    if ($row && count ($row) > 4 && substr (trim ($row [0]), 0, 1) != '#') {
      list ($iplow, $iphigh, , , $iso) = $row;

      if (strlen ($iso) != 2)
        echo "Invalid ISO code: ", $iso, "\n";
      else {
        // Translate IP to 4-byte binary strings
        list ($blow, $bgroup)  = ip2bin ($iplow);
        list ($bhigh)          = ip2bin ($iphigh);

  //      $tlow = substr ($blow, 0, 3) . chr ((ord ($blow [3]) + ord ($bhigh [3])) >> 1);
  //      $testdata .= $blow . $iso . $bhigh . $iso . $tlow . $iso;

        // New index group?
        if ($group !== $bgroup) {
          $group = $bgroup;
          $index [$group] = array ($blow, $datapos);
          $data [$group] = '';
        }

        // Add IP/county to data
        $data [$group] .= $blow . $iso;
        $datapos += 6;
        $count ++;
      }
    }

  // Build the index using the stored data locations and chunk lengths
  // The index file contains 12-byte records, grouped into three 32-bit dwords.
  // The first is an IP block address, the second contains the position in the data
  // file, and the third is the block length for that IP group.

  $indexbin = '';
  $maxlen = 0;
  foreach ($index as $group => &$index0) {
    $len = strlen ($data [$group]);
    $maxlen = max ($maxlen, $len);
    $indexbin .= $index0 [0] . pack ('LL', $index0 [1], $len);
  }

  $databin = implode ('', $data);
  $databin .= "\xFF\xFF\xFF\xFFZZ"; // Add one more for guaranteed upper bound
  fclose ($fp);

  // Save data files
  if ($file == '') $dat_file = 'ip2country.dat'; else $dat_file = dirname ($file).'/ip2country.dat';

  if ($count < 10000) {
    echo "Invalid input file, file ip2country.dat not generated.\n";
    @unlink ($dat_file);
  } else {
      file_put_contents ($dat_file, pack ('LL', strlen ($indexbin), strlen ($databin)) . $indexbin . $databin);
//      file_put_contents ('test.dat', $testdata);

      // Display statistics
      echo "$count records processed in ", time() - $t0, " seconds\r\n";
      echo count ($data). " index records with max group length = $maxlen\r\n";
    }
}

if (strpos ($_SERVER ['REQUEST_URI'], 'includes/geo/process_csv.php') !== false) {
  process_csv (dirname (__FILE__).'/IpToCountry.csv');
}
