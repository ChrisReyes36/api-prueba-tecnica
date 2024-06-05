<?php

namespace App\Traits;

trait JsonResponseTrait
{
    protected function jsonResponse($data, $status = 200, $errors = '')
    {
        return response()->json([
            'status' => $status,
            'data' => $data,
            'errors' => $errors
        ], $status);
    }
}
