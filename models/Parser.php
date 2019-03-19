<?php
/**
 * Created by PhpStorm.
 * User: root
 * Email: exbond@mail.ru
 * Date: 19.03.19
 * Time: 10:08
 */

namespace app\models;

use yii\base\ErrorException;
use yii\web\HttpException;

class Parser
{
    private $source;
    private $result = [];

    public function __construct($source)
    {
        $this->source = $source;
    }

    /**
     * @param bool $ignoreWordCase
     * @return string
     * Начинаем обработку контента
     */
    public function parse($ignoreWordCase = false)
    {
        $content = $this->getContent();
        $content = $this->removeSpecChars($content);

        if($ignoreWordCase) {
            $content = mb_strtolower($content);
        }

        $words = explode(" ", $content);

        if(count($words)>0) {
            foreach ($words as $word) {
                if(empty($word)) continue;
                if(array_key_exists($word, $this->result)) {
                    $this->result[$word] += 1;
                } else {
                    $this->result[$word] = 1;
                }
            }

            $this->sortData();

        } else {
            return "На данном ресурсе нет контента";
        }
    }

    /**
     * @return array
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @return string
     * @throws HttpException
     */
    public function getContent()
    {
        try {
            return file_get_contents($this->source);
        }
        catch (ErrorException $e) {
            throw new HttpException(400, "Не могу получить контент с ресурса {$this->source}.\n Ошибка: " . $e->getMessage());
        }
    }

    /**
     * Сортируем данные
     */
    public function sortData()
    {
        $raw_arr = [];
        foreach ($this->result as $word=>$cnt) {
            $raw_arr[] = ["word"=>$word, "count"=>$cnt];
        }

        usort($raw_arr, function($a,$b) {
            if ($a['count'] > $b['count']) {
                return 0;
            } elseif ($a['count'] < $b['count']) {
                return 1;
            } elseif ($a['word'] > $b['word']) {
                return 1;
            } else {
                return 0;
            }
        });

        $normalize_data = [];
        for($i=0; $i<count($raw_arr); $i++) {
            array_push($normalize_data, ["word"=>$raw_arr[$i]['word'], "count"=>$raw_arr[$i]['count']]);
        }

        $this->result = $normalize_data;
    }


    /**
     * @param $str
     * @return mixed
     * Фильтруем ненужные символы
     */
    public function removeSpecChars($str)
    {
        $str = preg_replace("#[[:punct:]]#", "", $str);
        $str = preg_replace('/[0-9]+/', '', $str);

        $str = str_replace("–", "", $str);
        $str = str_replace("«", "", $str);
        $str = str_replace("»", "", $str);

        return $str;
    }
}