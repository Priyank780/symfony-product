<?php

namespace App\Response;

/**
 * Class NotFoundProblemDetailsResponse.
 */
class NotFoundProblemDetailsResponse extends AbstractProblemDetailsResponse
{
    public const STATUS = 404;
    public const TITLE = 'Not Found';
    public const NOT_FOUND_CODE = 'f315b0c1-6f7f-4dd5-9f37-76f9dbb7e4ca';

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
