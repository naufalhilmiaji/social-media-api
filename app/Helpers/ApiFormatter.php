<?php

namespace App\Helpers;

class ApiFormatter {
    protected static $response = [
        'code' => null,
        'success' => null,
        'response' => null,
    ];

    public static function createApi($code = null, $success = null, $data) {
        self::$response['code'] = $code;
        self::$response['success'] = $success;
        self::$response['response'] = $data;

        return response()->json(self::$response, self::$response['code']);
    }
}
