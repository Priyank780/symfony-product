<?php

namespace App\Response;

use Symfony\Component\HttpFoundation\JsonResponse;

class ProblemJsonResponse extends JsonResponse
{
    public function __construct($data = null, int $status = 200, array $headers = [])
    {
        $headers['Access-Control-Allow-Origin'] = '*';
        $headers['content-type'] = 'application/problem+json';
        parent::__construct($data, $status, $headers, true);
    }
}
