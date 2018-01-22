<?php

$timestamp = $_GET['timestamp'];
$nonce     = $_GET['nonce'];
$tokenn    = "syjs10test";
$signature = $_GET['signature'];
$array     = array($timestamp, $nonce, $tokenn);
sort($array);
$tmpstr = implode('', $array);
$tmpstr = sha1($tmpstr);
if ($tmpstr == $signature) {
    echo $_GET['echostr'];
    exit();
}
