<?php

namespace App\Response;

use Symfony\Component\HttpFoundation\Response;

class HttpMethodNotAllowedProblemDetailsResponse extends AbstractProblemDetailsResponse
{
    public const STATUS = Response::HTTP_METHOD_NOT_ALLOWED;
    public const TITLE = 'Http Method Not Allowed';
    public const HTTP_METHOD_NOT_ALLOWED_CODE = '4f4e27c7-0b6b-41b8-9fdb-e89562f88f96';

    public function __construct(string $title, string $detail, string $type)
    {
        $this->title = $title;
        $this->detail = $detail;
        $this->type = $type;
    }
}
