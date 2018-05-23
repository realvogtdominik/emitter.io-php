<?php

require("../emitter.php");

$server = "mqtt.example.com";
$port = 1883;
$key = 'key-key-key-key';
$channel = 'test';



$emitter = new \emitter\emitter(array(
    'server' => $server,
    'port'   => $port,
));


$emitter->publish(
    array(
        'key'     => $key,
        'channel' => $channel,
        'ttl' => 5,
        'message' => array(
            'blah'    => 'gggg',
            'name'     => 'jimbob',

        ),
    )
);