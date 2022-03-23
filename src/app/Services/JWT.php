<?php


namespace App\Services;

class JWT {
    public static function encode($data) {
        return \Firebase\JWT\JWT::encode($data, env('APP_KEY'), 'HS256');
    }

    public static function decode($jwt) {
        return \Firebase\JWT\JWT::decode($jwt, new \Firebase\JWT\Key(env('APP_KEY'), 'HS256'));
    }
}