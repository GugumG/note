<?php

namespace App\Helpers;

class ThemeHelper
{
    /**
     * Determine if a hex color is "dark" or "light" and return contrast color.
     * USER Preferences: Light (#f7f7f7), Dark (#2c2c2c)
     */
    public static function getContrastColor($hexColor)
    {
        $hex = str_replace('#', '', $hexColor);
        if (strlen($hex) == 3) {
            $r = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1));
            $g = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1));
            $b = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1));
        } else {
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
        }
        $yiq = (($r * 299) + ($g * 587) + ($b * 114)) / 1000;
        return ($yiq >= 150) ? '#2c2c2c' : '#f7f7f7';
    }
}
