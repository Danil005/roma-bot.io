<?php

namespace Roma\Commands;

use Roma\Traits\Command;
use VK\Client\VKApiClient;

class Stats
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
            'рома стат',
        ];
    }

    public function main(): void
    {
        if ($this->redis->get('rpg:' . $this->from_id . ':start') == "true") {

            $message = "Ваша статиситка: \n" . "\n";
            $message .= "Уровень: " . $this->redis->get("rpg:{$this->from_id}:level") . "\n";
            $message .= "Опыт: " . $this->redis->get("rpg:{$this->from_id}:exp") . "\n";
            $message .= "Деньги: " . $this->redis->get("rpg:{$this->from_id}:money") . "\n";
            $message .= "Работа: " . $this->redis->get("rpg:{$this->from_id}:work") . "\n";
            $this->sendMessageChat($message);

        } else {
            $this->sendMessageChat("Для начала начните играть! [Команда: Рома хочу в рпг]");
        }
    }

}