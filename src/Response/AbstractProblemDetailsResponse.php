<?php

namespace App\Response;

use OpenApi\Annotations as OA;

/**
 * Class AbstractProblemDetailsResponse.
 *
 * @see https://tools.ietf.org/html/rfc7807
 */
abstract class AbstractProblemDetailsResponse
{
    /**
     * A URI reference [RFC3986] that identifies the problem type.
     */
    protected string $type;

    /**
     * A short, human-readable summary of the problem type.
     *
     * @OA\Parameter(name="title", examples={NotFoundProblemDetailsResponse::TITLE})
     */
    protected string $title;

    /**
     * A human-readable explanation specific to this occurrence of the problem.
     */
    protected string $detail;

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDetail(): string
    {
        return $this->detail;
    }

    public function setDetail(string $detail): self
    {
        $this->detail = $detail;

        return $this;
    }
}
