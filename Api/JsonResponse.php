<?php

namespace Api;

class JsonResponse
{

    public function __construct($arrayBody, $code = null)
    {
        $code = (int)$code;

        if (!empty($code)) {
            http_response_code($code);
        }

        header('Content-Type: application/json');

        if (is_string($arrayBody)) {
            $res = $arrayBody;
        } elseif (is_iterable($arrayBody)) {
            $res = json_encode($arrayBody);
        } else {
            $res = json_encode(['error' => 'Wrong response body type!']);
        }

        echo $res;
    }

}