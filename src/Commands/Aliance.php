<?php

namespace Roma\Commands;

use Roma\Traits\Command;
use VK\Client\VKApiClient;

class Aliance
{
    use Command;

    protected $message;
    protected $access_token;
    protected $peer_id;
    protected $message_array;

    public function __construct(array $object)
    {
        $this->message = $object['message'];
        $this->peer_id = $object['peer_id'];
        $this->access_token = $object['access'];
        $this->message_array = $object['message_array'];
    }

    public function call(): array
    {
        return [
            'за альянс'
        ];
    }

    public function main(): void
    {
        $this->sendMessageChat("Альянас сосед :)");
    }
}