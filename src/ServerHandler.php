<?php

namespace Roma;

use VK\CallbackApi\Server\VKCallbackApiServerHandler;

class ServerHandler extends VKCallbackApiServerHandler
{
    const SECRET = '1123151sfafasf1234';
    const GROUP_ID = 170419631;
    const CONFIRMATION_TOKEN = '262d47ed';

    public $message;
    public $peer_id;
    public $from_id;

    public function confirmation(int $group_id, ?string $secret)
    {
        if ($secret === static::SECRET && $group_id === static::GROUP_ID) {
            echo static::CONFIRMATION_TOKEN;
        }
    }

    public function messageNew(int $group_id, ?string $secret, array $object)
    {
        $this->message = $object['text'];
        $this->peer_id = $object['peer_id'];
        $this->from_id = $object['from_id'];

        

        echo 'ok';
    }
}