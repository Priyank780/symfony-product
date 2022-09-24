<?php

namespace App\Response;

/**
 * Class ValidationFailedProblemDetailsResponse.
 */
class ValidationFailedProblemDetailsResponse extends AbstractProblemDetailsResponse
{
    public const STATUS = 400;
    public const TITLE = 'Validation Failed';
    public const CODE = 'd24371b1-7b69-415b-b584-ec7633a78657';

    /**
     * @var Violation[]
     */
    protected array $violations;

    /**
     * ValidationFailedProblemDetailsResponse constructor.
     */
    public function __construct(string $title, string $detail, string $type, array $violations = [])
    {
        $this->title = $title;
        $this->detail = $detail;
        $this->type = $type;
        $this->violations = $violations;
    }

    /**
     * @return Violation[]
     */
    public function getViolations(): array
    {
        return $this->violations;
    }

    /**
     * @param Violation[] $violations
     *
     * @return $this
     */
    public function setViolations(array $violations): self
    {
        $this->violations = $violations;

        return $this;
    }

    public function addViolation(Violation $violation)
    {
        $this->violations[] = $violation;
    }
}
