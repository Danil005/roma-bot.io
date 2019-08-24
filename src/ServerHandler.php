<?php

namespace Roma;

use Roma\Commands\CommandsController;
use VK\CallbackApi\Server\VKCallbackApiServerHandler;
use VK\Client\VKApiClient;

class ServerHandler extends VKCallbackApiServerHandler
{
    const SECRET = '1123151sfafasf1234';
    const GROUP_ID = 170419631;
    const CONFIRMATION_TOKEN = '262d47ed';
    const ACCESS_TOKEN = "2f9e3f92aff9937f2c2814c0820e336dd9e322fadc8d48646d67ef4f74f8b856ca0267959b60c35e0f9fd";


    const LEVELS = [
        1 => 100,
        2 => 1000,
        3 => 3000,
        4 => 6000,
        5 => 10000,
        6 => 15000,
        7 => 20000
    ];

    public $object;

    public $vk;

    public function __construct()
    {
        $this->vk = new VKApiClient();
    }

    public function confirmation(int $group_id, ?string $secret)
    {
        if ($secret === static::SECRET && $group_id === static::GROUP_ID) {
            echo static::CONFIRMATION_TOKEN;
        }
    }

    public function messageNew(int $group_id, ?string $secret, array $object)
    {

        $this->object = [
            'message' => mb_strtolower(trim($object['text'])),
            'peer_id' => $object['peer_id'],
            'from_id' => $object['from_id'],
            'access' => self::ACCESS_TOKEN,
        ];

        $options = array(
            'servers' => array(
                array('host' => '127.0.0.1', 'port' => 6379),
            )
        );

        $redis = new \Rediska($options);


        if( $redis->get("rpg:{$object['from_id']}:start") == "true" ) {
            $exp = $redis->get('rpg:' . $object['from_id'] . ':exp');
            $level = $redis->get('rpg:' . $object['from_id'] . ':level');

            $redis->set('rpg:' . $object['from_id'] . ':exp', $exp + 0.1);

            foreach (self::LEVELS as $key => $value) {
                if ($exp >= $value && $level != $key) {
                    $redis->set('rpg:' . $object['from_id'] . ':level', $key);
                }
            }
        }

        $this->object['message_array'] = explode(' ', $this->object['message']);

        CommandsController::execute($this->object);

        echo 'ok';
    }
}