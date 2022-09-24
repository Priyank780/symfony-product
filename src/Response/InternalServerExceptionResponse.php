<?php

namespace App\Response;

use App\Exception\InternalServerErrorException;

class InternalServerExceptionResponse extends AbstractProblemDetailsResponse
{
    public const STATUS = 500;
    public const TITLE = 'InternalServerError';
    public const INTERNAL_SERVER_ERROR_CODE = InternalServerErrorException::INTERNAL_SERVER_ERROR_CODE;

    public function __construct(string $title, string $detail, string $type)
    {
        $this->title = $title;
        $this->detail = $detail;
        $this->type = $type;
    }
}
