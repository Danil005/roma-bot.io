<?php

namespace Roma\Traits;

use VK\Client\VKApiClient;

trait Command
{
    public $vk;
    public $var = [];
    public $redis;

    public $works = [
        'Раб | LV0',
        'Пиздабол | LV0',
        'Уборщик дерьма | LV1',
        'Пугало | LV 1',
        'Шпагоглотатель | LV2',
        'Библейский йордл | LV2',
        'Шлюха | LV2',
        'Королевский хуеносец | LV3',
        'Шут королевской особи | LV4',
        'Королева | LV5',
        'Король | LV6',
        'Ботрак Polydev | LV7'
    ];



    protected $similar_percent = 80;

    public function execute()
    {
        $options = array(
            'servers'   => array(
                array('host' => '127.0.0.1', 'port' => 6379),
            )
        );

        $this->redis = new \Rediska($options);
        if ($this->formatText($this->call(), $this->message)) {
            $this->vk = new VKApiClient();
            return $this->main();
        }
    }

    protected function getUserVk()
    {
        $response = $this->vk->users()->get($this->access_token, [
            'user_ids' => [$this->from_id]
        ]);

        return $response[0];
    }



    protected function sendMessageChat(string $message = "", array $attachments = []): void
    {
        $this->vk->messages()->send($this->access_token, [
            'peer_id' => $this->peer_id,
            'random_id' => rand(0, 1000),
            'message' => $message,
            'attachment' => $attachments
        ]);
    }

    public function getSimilarity($s1, $s2)
    {
        $p1 = $this->getSimilarityByWords($s1, $s2);
        $p2 = $this->getSimilarityCharacters($s1, $s2);
        return $p1 >= $p2 ? $p1 : $p2;
    }
    private function getSimilarityByWords($s1, $s2)
    {
        $str1 = explode(' ', preg_replace('/\s+/', ' ', preg_replace('/[\?!,;\.]/', '', strtolower($s1))));
        $str2 = explode(' ', preg_replace('/\s+/', ' ', preg_replace('/[\?!,;\.]/', '', strtolower($s2))));
        $matches = [];
        $word_matches = [];
        $all_word_count = 0;
        $word_count = 0;
        $max_word_start = 0;
        $max_word_end = 0;
        $strlen = [];
        foreach ($str1 as $w1) {
            if($w1=='')
                continue;
            $max_word_start = $all_word_count;
            foreach ($str2 as $w2) {
                if($w2=='')
                    continue;
                $matches[$all_word_count] = 0;
                $st1 = 0;
                $st2 = 0;
                $l1 = strlen($w1);
                $l2 = strlen($w2);
                for ($i = 0; $i < $l1; $i ++) {
                    for ($j = $st2; $j < $l2; $j ++) {
                        if ($w1[$i] == $w2[$j]) {
                            $matches[$all_word_count] += 1;
                            $st1 = $i + 1;
                            $st2 = $j + 1;
                            break;
                        }
                    }
                }
                $matches[$all_word_count] = (2 * $matches[$all_word_count]) / ($l1 + $l2);
                $all_word_count ++;
            }
            $max_word_end = $all_word_count - 1;
            $strlen[$word_count] = strlen($w1);
            $word_matches[$word_count ++] = $this->getMax($matches, $max_word_start, $max_word_end);
        }
        $sum = 0;
        $count = 0;
        foreach ($word_matches as $i => $m) {
            $sum += $m * $strlen[$i];
            $count += $strlen[$i];
        }
        return (100 * $sum) / $count;
    }
    private function getSimilarityCharacters($s1, $s2)
    {
        $str1 = preg_replace('/(\s+|[\?!,;\.])/', '', strtolower($s1));
        $str2 = preg_replace('/(\s+|[\?!,;\.])/', '', strtolower($s2));
        $matches = 0;
        $l1 = strlen($str1);
        $l2 = strlen($str2);
        $st2 = 0;
        for ($i = 0; $i < $l1; $i ++) {
            for ($j = $st2; $j < $l2; $j ++) {
                if ($str1[$i] == $str2[$j]) {
                    $matches ++;
                    $st2 = $j + 1;
                    break;
                }
            }
        }
        return (200 * $matches) / ($l1 + $l2);
    }
    private function getMax(&$matches, $max_word_start, $max_word_end)
    {
        $max = $matches[$max_word_start];
        for ($i = $max_word_start + 1; $i <= $max_word_end; $i ++) {
            if ($max < $matches[$i])
                $max = $matches[$i];
        }
        return $max;
    }

    public function startAs(string $text, string $message)
    {
        $textFromBot = preg_replace("/(?![.=$'€%-])\p{P}/u", "", $message);
        $text1 = preg_replace("/(?![.=$'€%-])\p{P}/u", "", $text);
        $firstWord = explode(' ', $text1)[0];
        $firstWordFromBot = explode(' ', $textFromBot)[0];
        return substr($firstWord, 1) == $firstWordFromBot;
    }

    /**
     * Заканчивается на
     * @param string $text
     * @param string $message
     * @return bool
     */
    public function endAs(string $text, string $message)
    {
        $textFromBot = preg_replace("/(?![.=$'€%-])\p{P}/u", "", $message);
        $text1 = preg_replace("/(?![.=$'€%-])\p{P}/u", "", $text);
        $firstWord = explode(' ', $text1);
        $firstWordFromBot = explode(' ', $textFromBot);
        return substr($firstWord[count($firstWord) - 1], 0, -1) == $firstWordFromBot[count($firstWordFromBot) - 1];
    }

    /**
     * Содержит
     * @param string $text
     * @param string $message
     * @return bool
     */
    public function contains(string $text, string $message)
    {
        $textFromBot = preg_replace("/(?![.=$'€%-])\p{P}/u", "", $message);
        $text1 = preg_replace("/(?![.=$'€%-])\p{P}/u", "", $text);
        return stripos($textFromBot, $text1) !== false;
    }

    private function formatText(array $text, string &$message)
    {
        foreach ($text as $value) {
            if (substr($value, 0, 1) == '|') {
                $pr = ($this->similar_percent != null) ? $this->similar_percent : 75;
                return $this->getSimilarity($value, $message) > $pr;
            }
            if (substr($value, 0, 2) == "[|") {
                return $this->startAs($value, $message);
            }
            if (substr($value, -2, 2) == "|]") {
                return $this->endAs($value, $message);
            }
            if (substr($value, 0, 1) == "{" && substr($value, -1, 1) == "}") {
                return $this->contains($value, $message);
            }
            if (substr($value, 0, 1) == '>') {
                $count = mb_substr($value, 1, 1);
                $first = explode(" ", substr($value, 2), $count);
                $second = explode(" ", $message);


                for($i=0; $i < $count; $i++) {
                    if( $first[$i] != $second[$i] ) {
                        return false;
                    }
                }
                return true;
            }
        }

        return in_array($message, $text);
    }
}