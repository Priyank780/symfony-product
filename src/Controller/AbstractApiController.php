<?php

namespace App\Controller;

use App\Entity\Job;
use App\Entity\Product;
use App\Factory\ErrorResponseFactory;
use App\Repository\ProductRepository;
use App\Request\ProductCreateRequest;
use App\Request\Proposal\ProposalCreateRequest;
use App\Response\ProductInfo;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AbstractApiController extends AbstractController
{
    public function __construct(protected ErrorResponseFactory $errorResponseFactory)
    {
    }

    protected function jsonConstraintViolationList(
        ConstraintViolationListInterface $constraintViolationList,
        int $status = Response::HTTP_BAD_REQUEST,
        array $headers = ['content-type' => 'application/problem+json'],
        array $context = []
    ): JsonResponse {
        $response = $this->errorResponseFactory->createBadRequest($constraintViolationList);

        return $this->json($response, $status, $headers, $context);
    }
}
