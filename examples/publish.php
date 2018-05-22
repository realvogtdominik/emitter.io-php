<?php

require("../emitter.php");

$server = "mqtt.example.com";
$port = 1883;
$key = 'key-key-key-key';
$channel = 'test';


$emitter = new emitter($server, $port, $key, $channel);
$emitter->publish('yoyoyo');
