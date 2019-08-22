<?php

namespace Roma;

use Roma\Commands\CommandsController;
use VK\CallbackApi\Server\VKCallbackApiServerHandler;
use VK\Client\VKApiClient;

class ServerHandler extends VKCallbackApiServerHandler
{
    const SECRET = '1123151sfafasf12342';
    const GROUP_ID = 170419631;
    const CONFIRMATION_TOKEN = '262d47cd';
    const ACCESS_TOKEN = "2f9e3f92aff1937f2c2814c0820e336dd9e322fadc8d48646d67ef4f74f8b856ca0267959b60c35e0f9fd";

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
            'access' => self::ACCESS_TOKEN
        ];

        CommandsController::execute($this->object);

        echo 'ok';
    }
}