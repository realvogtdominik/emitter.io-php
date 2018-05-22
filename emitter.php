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
    protected $key;
    protected $channel;

    public function __construct($server, $port, $key, $channel, $uniqueId = 0)
    {

        if ($uniqueId == 0) {
            $uniqueId = sha1(microtime() . $key, $channel);
        }

        $this->key = $key;
        $this->channel = $channel;


        if (! $this->startsWith($this->channel, '/')) {
            $this->channel = '/' . $this->channel;
        }

        if (! $this->endsWith($this->channel, '/')) {
            $this->channel .= '/';
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

    public function publish($message)
    {
        $this->emitter->publish($this->key . $this->channel, $message, 0);

    }

}