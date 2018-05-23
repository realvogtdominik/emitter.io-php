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
     * Emitter constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {

        $this->_config = $config;


        $server = $config['server'];
        $port = $config['port'];
        $uniqueId = isset($config['uniqueId']) ? $config['uniqueId'] : sha1(microtime() . $server . $port);


        $username = '';
        $password = '';
        $this->emitter = new phpMQTT($server, $port, $uniqueId);
        if (! $this->emitter->connect(true, NULL, $username, $password)) {
            return false;
        }
        return true;
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
     * @param array $data
     */
    public function publish(array $data)
    {
        $key = $data['key'];
        $channel = $data['channel'];
        $message = $data['message'];

        $channel = $this->parseChannel($channel);

        if (is_array($message) || is_object($message)) {
            $message = json_encode($message);
        }
        $this->emitter->publish($key . $channel, $message, 0);
    }


}