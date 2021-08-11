<?php
    // Add to zip all files in subfolders
    require_once( __DIR__ .'/str.php');
    $dir = '/usr/www/zip2';
    $fname_log = 'zip_file.log';
    $fname_run = 'run.sh';
    $ar_file = dir_to_array_nr($dir,true,false);
    $str = '#!/bin/sh' . PHP_EOL;
    foreach($ar_file as $dir_name) {
        $bname = basename($dir_name);
        echo "$bname $dir_name" . PHP_EOL;
        $str .= 'zip -r -m -j "'.$bname.'.zip" "'.$dir_name.'/" ' . PHP_EOL;
    }
    file_put_contents($fname_run,$str);
    chmod($fname_run,0755);
    