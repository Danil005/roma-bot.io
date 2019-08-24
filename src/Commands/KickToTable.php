<?php

namespace Roma\Commands;

use Roma\Traits\Command;
use VK\Client\VKApiClient;

class KickToTable
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
            '>3рома дай ляпос'
        ];
    }

    public function main(): void
    {
        $response = $this->vk->users()->get($this->access_token, [
            'user_ids' => [substr(explode("|", $this->message_array[3])[0], 3)],
        ]);

        $rand = rand(0, 100);
        if( $rand < 50 ) {
            $this->sendMessageChat("Получи ляпос, @id" . $response[0]['id'] . '(' . $response[0]['first_name'] . ')');
        } else {
            $this->sendMessageChat("Иди нахуй, пока тебе не вломил!");

        }
    }
}