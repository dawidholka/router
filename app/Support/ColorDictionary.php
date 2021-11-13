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

    public static function getColorForStop(int $stopNumber): string
    {
        $range = 3;
        $weight = 122;

        if ($stopNumber < ($weight / $range)) {
            $red = 255 - ($stopNumber) * $range;
            $green = 255;
            $blue = 133;
        } else if ($stopNumber < 2 *($weight / $range)){
            $stopNumber = $stopNumber - 1*($weight / $range);
            $red = 133;
            $green = 255;
            $blue = 133 + ($stopNumber) * $range;
        } else if ($stopNumber < 3 *($weight / $range)){
            $stopNumber = $stopNumber - 2*($weight / $range);
            $red = 133;
            $green = 255 - ($stopNumber) * $range;
            $blue = 255;
        } else if ($stopNumber < 4 *($weight / $range)){
            $stopNumber = $stopNumber - 3*($weight / $range);
            $red = 133 + ($stopNumber) * $range;
            $green = 133;
            $blue = 133 + ($stopNumber) * $range;
        } else{
            $stopNumber = $stopNumber - 4*($weight / $range);
            $red = 255;
            $green = 133;
            $blue = 255 - ($stopNumber) * $range;
        }

        return "rgba($red,$green,$blue)";
    }
}
