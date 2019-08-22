<?php

namespace Roma\Commands;

use Roma\Traits\Command;
use VK\Client\VKApiClient;

class Yes
{
    use Command;

    protected $message;
    protected $access_token;
    protected $peer_id;

    public function __construct(array $object)
    {
        $this->message = $object['message'];
        $this->peer_id = $object['peer_id'];
        $this->access_token = $object['access'];
    }

    public function call(): array
    {
        return [
            'да рома',
            'Да рома?'
        ];
    }

    public function main(): void
    {
        $this->sendMessageChat('+');
    }
}