<?php

namespace App\Factory;

use App\Response\AlreadyExistsProblemDetailsResponse;
use App\Response\HttpMethodNotAllowedProblemDetailsResponse;
use App\Response\InternalServerExceptionResponse;
use App\Response\NotFoundProblemDetailsResponse;
use App\Response\ValidationFailedProblemDetailsResponse;
use App\Response\Violation;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Exception\ValidatorException;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class ErrorResponseFactory.
 */
class ErrorResponseFactory
{
    public function __construct(private readonly TranslatorInterface $translator)
    {
    }

    public function createBadRequest(
        ConstraintViolationListInterface $constraintViolations
    ): ValidationFailedProblemDetailsResponse {
        $title = $this->getResponseTitle(ValidationFailedProblemDetailsResponse::CODE);
        $detail = $this->getResponseDetail(ValidationFailedProblemDetailsResponse::CODE);
        $problemDetailsResponse = new ValidationFailedProblemDetailsResponse($title, $detail, $this->getType());

        /** @var ConstraintViolation $violation */
        foreach ($constraintViolations as $violation) {
            $problemDetailsResponse
                ->addViolation(Violation::fromConstraintViolation($violation, $this->translator));
        }

        return $problemDetailsResponse;
    }

    public function createNotFoundResponse(NotFoundHttpException $e): NotFoundProblemDetailsResponse
    {
        return $this->createNotFoundExceptionResponse($e->getMessage());
    }

    public function createInternalServerErrorResponse(): InternalServerExceptionResponse
    {
        $title = $this->getResponseTitle(InternalServerExceptionResponse::INTERNAL_SERVER_ERROR_CODE);
        $detail = $this->getResponseDetail(InternalServerExceptionResponse::INTERNAL_SERVER_ERROR_CODE);

        return new InternalServerExceptionResponse($title, $detail, $this->getType());
    }

    public function createValidationFailedResponse(ValidatorException $e): ValidationFailedProblemDetailsResponse
    {
        $title = $this->getResponseTitle($e->getMessage());
        $detail = $this->getResponseDetail($e->getMessage());

        return new ValidationFailedProblemDetailsResponse($title, $detail, $this->getType());
    }

    public function createAlreadyExistsResponse(): AlreadyExistsProblemDetailsResponse
    {
        $title = $this->getResponseTitle(AlreadyExistsProblemDetailsResponse::ALREADY_EXISTS_CODE);
        $detail = $this->getResponseDetail(AlreadyExistsProblemDetailsResponse::ALREADY_EXISTS_CODE);

        return new AlreadyExistsProblemDetailsResponse($title, $detail, $this->getType());
    }

    public function createMethodNotAllowedResponse(
        MethodNotAllowedHttpException $e
    ): HttpMethodNotAllowedProblemDetailsResponse {
        return $this->createHttpMethodNotAllowedExceptionResponse($e->getMessage());
    }

    private function createHttpMethodNotAllowedExceptionResponse(
        string $message
    ): HttpMethodNotAllowedProblemDetailsResponse {
        if ($message === $this->translator
                ->trans($message, [], 'api_exceptions')) {
            $title = $this->getResponseTitle(HttpMethodNotAllowedProblemDetailsResponse::HTTP_METHOD_NOT_ALLOWED_CODE);
            $detail = $this
                ->getResponseDetail(HttpMethodNotAllowedProblemDetailsResponse::HTTP_METHOD_NOT_ALLOWED_CODE);
        } else {
            $title = $this->getResponseTitle($message);
            $detail = $this->getResponseDetail($message);
        }

        return new HttpMethodNotAllowedProblemDetailsResponse($title, $detail, $this->getType());
    }

    private function createNotFoundExceptionResponse(string $message): NotFoundProblemDetailsResponse
    {
        if ($message === $this->translator
                ->trans($message, [], 'api_exceptions')) {
            $title = $this->getResponseTitle(NotFoundProblemDetailsResponse::NOT_FOUND_CODE);
            $detail = $this->getResponseDetail(NotFoundProblemDetailsResponse::NOT_FOUND_CODE);
        } else {
            $title = $this->getResponseTitle($message);
            $detail = $this->getResponseDetail($message);
        }

        return new NotFoundProblemDetailsResponse($title, $detail, $this->getType());
    }

    private function getType(): string
    {
        // return $this->router->generate('route_name', [], UrlGeneratorInterface::ABSOLUTE_URL);
        return '';
    }

    public function getResponseTitle(string $translatorId): string
    {
        return $this->translator->trans($translatorId, [], 'api_exceptions_code');
    }

    public function getResponseDetail(string $translatorId): string
    {
        return $this->translator->trans($translatorId, [], 'api_exceptions');
    }
}
