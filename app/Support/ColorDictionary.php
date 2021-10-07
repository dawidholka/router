<?php

namespace App\Support;

use Illuminate\Support\Arr;

final class ColorDictionary
{
    const COLORS = [
        ['name' => 'aqua', 'value' => 'aqua'],
        ['name' => 'blueviolet', 'value' => 'blueviolet'],
        ['name' => 'cornflowerblue', 'value' => 'cornflowerblue'],
        ['name' => 'chartreuse', 'value' => 'chartreuse'],
        ['name' => 'coral', 'value' => 'coral'],
        ['name' => 'darkgray', 'value' => 'darkgray'],
        ['name' => 'darkorange', 'value' => 'darkorange'],
        ['name' => 'darksalmon', 'value' => 'darksalmon'],
        ['name' => 'hotpink', 'value' => 'hotpink'],
        ['name' => 'lightgreen', 'value' => 'lightgreen'],
        ['name' => 'yellowgreen', 'value' => 'yellowgreen'],
    ];

    public static function getRandomColor(): string
    {
        return Arr::random(self::COLORS)['value'];
    }
}
