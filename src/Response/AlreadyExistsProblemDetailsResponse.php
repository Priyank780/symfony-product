<?php

namespace App\Response;

use App\Exception\AlreadyExistsException;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AlreadyExistsProblemDetailsResponse.
 */
class AlreadyExistsProblemDetailsResponse extends AbstractProblemDetailsResponse
{
    public const STATUS = Response::HTTP_CONFLICT;
    public const TITLE = 'Already Exists';
    public const ALREADY_EXISTS_CODE = AlreadyExistsException::ALREADY_EXISTS_CODE;

    /**
     * UnauthorizedProblemDetailsResponse constructor.
     */
    public function __construct(string $title, string $detail, string $type)
    {
        $this->title = $title;
        $this->detail = $detail;
        $this->type = $type;
    }
}
