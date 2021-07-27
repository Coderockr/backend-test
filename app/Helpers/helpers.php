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
     * @param bool $show_seconds
     * @param bool $has_separator
     * @param string $separator
     * @return string
     */
    function dareHourBrFormat($data, $show_seconds = true, $has_separator = false, $separator = "") {
        $format = "d/m/Y" . ($has_separator ? " $separator " : " ") . "H:i" . ($show_seconds ? ":s" : "");
        return $data ? date($format, strtotime( str_replace("/", "-", $data) ) ) : $data;
    }
}
