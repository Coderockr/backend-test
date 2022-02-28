<?php
namespace Bootstrap;

class EnvironmentVariableLoader {
    public static function load($directory) {
        if (!file_exists($directory.'/.env')) {
            return false;
        }

        $variables = file($directory.'/.env');
        foreach ($variables as $variable) {
            putenv(trim($variable));
        }
    }
}