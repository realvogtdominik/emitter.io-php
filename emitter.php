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


    /**
     * emitter constructor.
     * @param string $server
     * @param int $port
     * @param string $uniqueId
     * @throws \Exception
     */
    public function __construct($server, $port, $uniqueId = '')
    {
        if ($uniqueId == '') {
            $uniqueId = sha1(microtime() . $server . $port);
        }

        $username = "";
        $password = "";
        $this->emitter = new phpMQTT($server, $port, $uniqueId);
        if (! $this->emitter->connect(true, NULL, $username, $password)) {
            Throw new \Exception('unable to connect');
        }
    }


    /**
     * @param string $channel
     * @return string
     */
    private function parseChannel($channel)
    {
        if (! $this->startsWith($channel, '/')) {
            $channel = '/' . $channel;
        }

        if (! $this->endsWith($channel, '/')) {
            $channel .= '/';
        }

        return $channel;
    }

    /**
     * @param $haystack
     * @param $needle
     * @return bool
     */
    private function startsWith($haystack, $needle)
    {
        $length = strlen($needle);

        return (substr($haystack, 0, $length) === $needle);
    }

    /**
     * @param $haystack
     * @param $needle
     * @return bool
     */
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


    /**
     * @param string $key
     * @param string $channel
     * @param mixed $message
     */
    public function publish($key, $channel, $message)
    {
        $channel = $this->parseChannel($channel);
        if(is_array($message) || is_object($message)){
            $message = json_encode($message);
        }
        $this->emitter->publish($key . $channel, $message, 0);
    }


}