<?php

namespace Roma\Traits;

use VK\Client\VKApiClient;

trait Command
{
    public $vk;

    public function execute()
    {
        if (in_array($this->message, $this->call())) {
            $this->vk = new VKApiClient();
            return $this->main();
        }
    }

    protected function sendMessageChat(string $message): void
    {
        $this->vk->messages()->send($this->access_token, [
            'peer_id' => $this->peer_id,
            'random_id' => rand(0, 1000),
            'message' => $message
        ]);
    }
}