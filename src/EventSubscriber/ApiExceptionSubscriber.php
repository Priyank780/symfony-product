<?php

namespace App\EventSubscriber;

use App\Exception\AlreadyExistsException;
use App\Factory\ErrorResponseFactory;
use App\Response\AbstractProblemDetailsResponse;
use App\Response\ProblemJsonResponse;
use JetBrains\PhpStorm\ArrayShape;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Exception\ValidatorException;
use Throwable;

/**
 * Class ApiExceptionSubscriber.
 */
class ApiExceptionSubscriber implements EventSubscriberInterface
{
    private SerializerInterface $serializer;
    private LoggerInterface $logger;
    private ErrorResponseFactory $errorResponseFactory;

    public function __construct(
        SerializerInterface $serializer,
        LoggerInterface $logger,
        ErrorResponseFactory $errorResponseFactory
    ) {
        $this->serializer = $serializer;
        $this->logger = $logger;
        $this->errorResponseFactory = $errorResponseFactory;
    }

    /**
     * {@inheritdoc}
     */
    #[ArrayShape([KernelEvents::EXCEPTION => 'array[]'])]
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => [
                ['onKernelException', 20],
            ],
        ];
    }

    public function onKernelException(ExceptionEvent $event)
    {
        $ex = $event->getThrowable();
        if ($ex instanceof AccessDeniedException) {
            // Let Symfony decide to return 401 or 403 status code.
            // Ref: https://symfony.com/doc/current/security/access_denied_handler.html
            return;
        }
        $statusCode = match (true) {
            $ex instanceof MethodNotAllowedHttpException => Response::HTTP_METHOD_NOT_ALLOWED,
            $ex instanceof NotFoundHttpException => Response::HTTP_NOT_FOUND,
            $ex instanceof ValidatorException, $ex instanceof AlreadyExistsException => Response::HTTP_BAD_REQUEST,
            default => Response::HTTP_INTERNAL_SERVER_ERROR,
        };

        $formatType = 'json';

        $json = $this->serializer->serialize($this->exceptionToErrorResponse($ex), $formatType, array_merge([
            'json_encode_options' => JsonResponse::DEFAULT_ENCODING_OPTIONS,
        ]));

        $response = new ProblemJsonResponse($json, $statusCode);

        $event->allowCustomResponseCode();
        $event->setResponse($response);
    }

    private function exceptionToErrorResponse(Throwable $ex): AbstractProblemDetailsResponse
    {
        $this->logger->info($ex->getMessage(), [$ex]);
        if ($ex instanceof NotFoundHttpException) {
            $errorResponse = $this->errorResponseFactory->createNotFoundResponse($ex);
        } elseif ($ex instanceof MethodNotAllowedHttpException) {
            $errorResponse = $this->errorResponseFactory->createMethodNotAllowedResponse($ex);
        } elseif ($ex instanceof ValidatorException) {
            $errorResponse = $this->errorResponseFactory->createValidationFailedResponse($ex);
        } elseif ($ex instanceof AlreadyExistsException) {
            $errorResponse = $this->errorResponseFactory->createAlreadyExistsResponse();
        } else {
            $this->logger->critical($ex->getMessage(), [$ex]);
            $errorResponse = $this->errorResponseFactory->createInternalServerErrorResponse();
        }

        return $errorResponse;
    }
}
