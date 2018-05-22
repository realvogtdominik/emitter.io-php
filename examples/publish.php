<?php

require("../emitter.php");

$server = "mqtt.example.com";
$port = 1883;
$key = 'key-key-key-key';
$channel = 'test';


$emitter = new emitter($server, $port);
$emitter->publish(
    $key,
    $channel,
    'yoyoyo'
);
