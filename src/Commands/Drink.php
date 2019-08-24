<?php

namespace Roma\Commands;

use Roma\Traits\Command;
use VK\Client\VKApiClient;

class Drink
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
            'рома выпьем',
            'рома выпьем?'
        ];
    }

    public function main(): void
    {
        $this->sendMessageChat('Уууух... Пивка для рывка!', ['photo-170419631_457239018']);
    }
}