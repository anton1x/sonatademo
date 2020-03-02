<?php


namespace App\Utils;


class TextFunctions
{

    public static function declOfNum($number, $titles)
    {
        $cases = array (2, 0, 1, 1, 1, 2);
        $format = $titles[ ($number%100 > 4 && $number %100 < 20) ? 2 : $cases[min($number%10, 5)] ];
        return sprintf($format, $number);
    }

    public static function rusDate(\DateTime $date)
    {
        $months = [
          1 => 'января',
          2 => 'февраля',
          3 => 'марта',
          4 => 'апреля',
          5 => 'мая',
          6 => 'июня',
          7 => 'июля',
          8 => 'августа',
          9 => 'сентября',
          10 => 'октября',
          11 => 'ноября',
          12 => 'декабря'
        ];

        $day = $date->format('d');
        $month = $months [$date->format('n')];
        $year = $date->format('Y');

        return sprintf('%d %s %d', $day, $month, $year);
    }

}