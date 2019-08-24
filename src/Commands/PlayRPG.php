<?php

namespace Roma\Commands;

use Roma\Traits\Command;
use VK\Client\VKApiClient;

class PlayRPG
{
    use Command;

    protected $message;
    protected $access_token;
    protected $peer_id;
    protected $message_array;
    protected $from_id;

    public function __construct(array $object)
    {
        $this->message = $object['message'];
        $this->peer_id = $object['peer_id'];
        $this->access_token = $object['access'];
        $this->message_array = $object['message_array'];
        $this->from_id = $object['from_id'];
    }

    public function call(): array
    {
        return [
            'рома хочу в рпг'
        ];
    }

    public function main(): void
    {
        $response = $this->vk->users()->get($this->access_token, [
            'user_ids' => [$this->from_id]
        ]);

        if( $this->redis->get('rpg:'.$response[0]['id'].':start') != "true" ) {
            $this->redis->set('rpg:' . $response[0]['id'] . ":start", "true");
            $this->redis->set('rpg:'.$response[0]['id'].":exp", 0);
            $this->redis->set('rpg:'.$response[0]['id'].":level", 0);
            $this->redis->set('rpg:'.$response[0]['id'].":money", 0);


            $this->sendMessageChat("Хорошо, " . $response[0]['first_name'] . ", добро пожаловать в Азерот!");
        } else {
            $this->sendMessageChat("Упс, вы уже вошли в мир!");
        }
    }
}