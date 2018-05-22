<?php
/**
 * Created by PhpStorm.
 * User: bob
 * Date: 22/05/2018
 * Time: 12:07
 */

namespace emitter;


use emitter\phpMQTT;

class emitter
{


    protected $emitter;


    public function __construct($server, $port, $uniqueId = 0)
    {
        if ($uniqueId == 0) {
            $uniqueId = sha1(microtime());
        }

        $username = "";
        $password = "";
        $this->emitter = new phpMQTT($server, $port, $uniqueId);
        if (! $this->emitter->connect(true, NULL, $username, $password)) {
            Throw new \Exception('unable to connect');
        }
    }


    private function startsWith($haystack, $needle)
    {
        $length = strlen($needle);

        return (substr($haystack, 0, $length) === $needle);
    }

    private function endsWith($haystack, $needle)
    {
        $length = strlen($needle);

        return $length === 0 ||
            (substr($haystack, -$length) === $needle);
    }


    public function disconnect()
    {
        $this->emitter->close();
    }

    public function publish($key, $channel, $message)
    {

        if (! $this->startsWith($channel, '/')) {
            $channel = '/' . $channel;
        }

        if (! $this->endsWith($channel, '/')) {
            $channel .= '/';
        }

        $this->emitter->publish($key . $channel, $message, 0);

    }

}