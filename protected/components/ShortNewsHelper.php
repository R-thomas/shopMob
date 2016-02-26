<?php

class ShortNewsHelper
{
    public static function getShortTextNews($text, $count_words)
    {
        $arr = explode(' ', $text);
        if (count($arr)>$count_words)
        {
            $arr = array_slice($arr, 0, $count_words);
            $text = implode(' ', $arr ); // Этот текст нужно обработать как...
            $text = rtrim($text, ',')."...";
            return $text;
            unset($arr);
        }
        else
        {
            return $text;
        }
    }
}