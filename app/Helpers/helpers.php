<?php

if (! function_exists('dateHourDBFormat')) {
    /**
     * Format date to database datetime format
     *
     * @param $data
     * @return string
     */
    function dateHourDBFormat($data) {
        return date("Y-m-d H:i:s", strtotime( str_replace("/", "-", $data) ) );
    }
}

if (! function_exists('dateDBFormat')) {
    /**
     * Format date to database date format
     *
     * @param $data
     * @return string
     */
    function dateDBFormat($data) {
        return date("Y-m-d", strtotime( str_replace("/", "-", $data) ) );
    }
}

if (! function_exists('dareHourBrFormat')) {
    /**
     * Format datetime to database format
     *
     * @param $data
     * @param bool $showSeconds
     * @param bool $hasSeparator
     * @param string $separator
     * @return string
     */
    function dareHourBrFormat($data, $showSeconds = true, $hasSeparator = false, $separator = "") {
        $format = "d/m/Y" . ($hasSeparator ? " $separator " : " ") . "H:i" . ($showSeconds ? ":s" : "");
        return $data ? date($format, strtotime( str_replace("/", "-", $data) ) ) : $data;
    }
}
