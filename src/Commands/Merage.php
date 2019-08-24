<?php

namespace Roma\Commands;

use Roma\Traits\Command;
use VK\Client\VKApiClient;

class Merage
{
    use Command;

    protected $message;
    protected $access_token;
    protected $peer_id;
    protected $message_array;
    protected $from_id;

    private $merg_text = [
        'Так веселились, что ебнул бабулю стулом. Теперь жених в тюрьме!',
        'Так тебе Илон Маск!',
        'Ну нихуя ж себе, кабриолет взорвали...',
        'ЗА ООРДУУУУУУ!',
        'Сергей ебнутый.',
        'Теперь они помечены QR-кодом.',
        'Сука, ебанный мехмат.. Сженил всех блять...'
    ];

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
            '>2рома пожени'
        ];
    }


    public function main(): void
    {
        if ($this->redis->get('rpg:' . $this->from_id . ':start') == "true") {

            if (isset($this->message_array[2]) && isset($this->message_array[3])) {


                $response1 = $this->vk->users()->get($this->access_token, [
                    'user_ids' => [$this->message_array[2]],
                    'name_case' => 'acc'
                ])[0];

                $response2 = $this->vk->users()->get($this->access_token, [
                    'user_ids' => [$this->message_array[3]],
                    'name_case' => 'acc'
                ])[0];
//
                $this->sendMessageChat($response1['first_name'].' '.$response2['first_name']);
//                $this->sendMessageChat('Ебать, ну с чо. С днем свадьбы. Надеюсь вы сдохните вместе!');
//                $this->sendMessageChat("ПОЗДРАВИМ БЛЯТЬ @{$id1}({$response1['first_name']}) и @id{$id2}({$response2['first_name']})");
//                $this->sendMessageChat($this->merg_text[rand(0, 6)]);

            }
        } else {
            $this->sendMessageChat("Для начала начните играть! [Команда: Рома хочу в рпг]");
        }
    }
}