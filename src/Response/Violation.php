<?php

namespace App\Response;

use Symfony\Component\Form\FormError;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class Violation.
 */
class Violation
{
    /**
     * @var string json pointer
     */
    protected string $pointer = '';
    /**
     * @var string a short, human-readable summary of the problem
     *             type
     */
    protected string $title = '';

    protected string $detail = '';

    public function getPointer(): string
    {
        return $this->pointer;
    }

    public function setPointer(string $pointer): self
    {
        $this->pointer = $pointer;

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

    /**
     * @return Violation
     */
    public static function fromConstraintViolation(
        ConstraintViolation $constraintViolation,
        TranslatorInterface $translator
    ) {
        $violation = new self();

        $violation
            ->setDetail($constraintViolation->getMessage())
            ->setPointer($violation->jsonPointerFromPath($constraintViolation))
        ;

        if (null !== $code = $constraintViolation->getCode()) {
            $violation->setTitle($translator->trans($code, [], 'api_exceptions_code'));
        }
        if (isset($constraintViolation->getConstraint()->payload['code'])) {
            $violation->setTitle($translator
                ->trans($constraintViolation
                    ->getConstraint()->payload['code'], [], 'api_exceptions_code'));
        }
        if (isset($constraintViolation->getConstraint()->payload['detail'])) {
            $violation->setDetail($constraintViolation->getConstraint()->payload['detail']);
        }

        return $violation;
    }

    public static function fromFormError(FormError $formError): self
    {
        $violation = new self();

        $violation
            ->setDetail($formError->getMessage())
            ->setPointer('')
        ;

        return $violation;
    }

    /**
     * @return string
     */
    protected function jsonPointerFromPath(ConstraintViolation $constraintViolation)
    {
        $propertyPath = $constraintViolation->getPropertyPath();
        if (isset($constraintViolation->getConstraint()->payload['propertyPath'])) {
            $propertyPath = $constraintViolation->getConstraint()->payload['propertyPath'];
        }

        $jsonPointer = '/'.$propertyPath;
        $jsonPointer = str_replace('.', '/', $jsonPointer);
        $jsonPointer = str_replace('[', '/', $jsonPointer);

        return str_replace(']', '', $jsonPointer);
    }
}
