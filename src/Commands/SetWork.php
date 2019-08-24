<?php

namespace Roma\Commands;

use Roma\Traits\Command;
use VK\Client\VKApiClient;

class SetWork
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
            '>4рома устроиться на работу'
        ];
    }

    public function main(): void
    {
        $response = $this->vk->users()->get($this->access_token, [
            'user_ids' => [$this->from_id]
        ])[0];

        if ($this->redis->get('rpg:' . $response['id'] . ':start') == "true") {

            if (isset($this->message_array[4])) {
                if (((int)$this->message_array[4] > 0 && (int)$this->message_array[4] <= count($this->works))) {
                    $level = $this->redis->get('rpg:' . $response['id'] . ':level');

                    $work = $this->works[(int)$this->message_array[4] - 1];

                    $need_level = substr(explode('|', $work)[1], 3);


                    if( $level >= $need_level ) {
                        $this->sendMessageChat('Теперь твоя работа ' . trim(explode('|', $work)[0]) . ". Удачи!");
                        $this->redis->set('rpg:'.$response['id'].':work', trim(explode('|', $work)[0]));
                    } else {
                        $this->sendMessageChat('Не дорос, сосунок.');
                    }
                } else {
                    $this->sendMessageChat('Нет такой хуйни.');
                }
            } else {
                $this->sendMessageChat("Напиши блять номер работы.");
            }

        } else {
            $this->sendMessageChat("Для начала начните играть! [Команда: Рома хочу в рпг]");
        }
    }

}