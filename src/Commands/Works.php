<?php

namespace Roma\Commands;

use Roma\Traits\Command;
use VK\Client\VKApiClient;

class Works
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
            'рома работы'
        ];
    }

    public function main(): void
    {
        $response = $this->vk->users()->get($this->access_token, [
            'user_ids' => [$this->from_id]
        ])[0];

        if( $this->redis->get('rpg:'.$response['id'].':start') == "true" ) {

            $message = "Список хуевой работы: \n\n";

            $message .= "Ваш уровень: " . $this->redis->get('rpg:'.$response['id'].':level') . "\n\n";
            $i = 1;
            foreach ($this->works as $key=>$value) {
                $message .= $i.". ".$value . "\n";
                $i++;
            }

            $this->sendMessageChat($message);
        } else {
            $this->sendMessageChat("Для начала начните играть! [Команда: Рома хочу в рпг]");
        }
    }

}