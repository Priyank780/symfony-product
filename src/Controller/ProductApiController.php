<?php

namespace App\Controller;

use App\Entity\Product;
use App\Factory\ErrorResponseFactory;
use App\Repository\ProductRepository;
use App\Request\ProductCreateRequest;
use App\Request\ProductUpdateRequest;
use App\Response\ProductInfo;
use App\Response\ProductList;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;

class ProductApiController extends AbstractApiController
{
    public function __construct(protected ErrorResponseFactory $errorResponseFactory)
    {
        parent::__construct($this->errorResponseFactory);
    }

    #[Route('/api/v1/products', name: 'app_api_products_list', methods: [Request::METHOD_GET])]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: 'Return product list',
        content: new OA\JsonContent(ref: new Model(type: ProductList::class))
    )]
    public function listAction(ProductRepository $productRepository): JsonResponse
    {
        $list = $productRepository->findAll();
        $jsonList = array_map(fn(Product $p) => ProductInfo::getInstance($p), $list);
        return $this->json($jsonList, Response::HTTP_OK);
    }

    #[Route('/api/v1/products', name: 'app_api_products_create', methods: [Request::METHOD_POST])]
    #[OA\RequestBody(
        description: 'Provide Product details to Create',
        content: new OA\JsonContent(ref: new Model(type: ProductCreateRequest::class))
    )]
    #[OA\Response(
        response: Response::HTTP_CREATED,
        description: 'Return created product information',
        content: new OA\JsonContent(ref: new Model(type: ProductInfo::class))
    )]
    public function createAction(ProductRepository $productRepository, ValidatorInterface $validator, ProductCreateRequest $productCreateRequest): JsonResponse
    {
        $errors = $validator->validate($productCreateRequest);
        if (count($errors) > 0) {
            return $this->jsonConstraintViolationList($errors);
        }
        $product = new Product();
        $product
            ->setName($productCreateRequest->getName())
            ->setPrice($productCreateRequest->getPrice())
            ->setCreatedAt(new \DateTimeImmutable())
            ->setDescription($productCreateRequest->getDescription());
        $productRepository->save($product, true);

        return $this->json(ProductInfo::getInstance($product), Response::HTTP_CREATED);
    }

    #[Route('/api/v1/products/{id}', name: 'app_api_products_update', methods: [Request::METHOD_PUT])]
    #[OA\RequestBody(
        description: 'Provide Product details to update',
        content: new OA\JsonContent(ref: new Model(type: ProductUpdateRequest::class))
    )]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: 'Return updated product information',
        content: new OA\JsonContent(ref: new Model(type: ProductInfo::class))
    )]
    public function updateAction(ProductRepository $productRepository, int $id, ValidatorInterface $validator, ProductUpdateRequest $productUpdateRequest): JsonResponse
    {
        $product = $productRepository->find($id);
        if (is_null($product)) {
            return $this->json([], Response::HTTP_NOT_FOUND);
        }

        $errors = $validator->validate($productUpdateRequest);
        if (count($errors) > 0) {
            return $this->jsonConstraintViolationList($errors);
        }
        $product
            ->setName($productUpdateRequest->getName())
            ->setPrice($productUpdateRequest->getPrice())
            ->setDescription($productUpdateRequest->getDescription());
        $productRepository->save($product, true);
        return $this->json(ProductInfo::getInstance($product), Response::HTTP_OK);
    }

    #[Route('/api/v1/products/{id}', name: 'app_api_products_delete', methods: [Request::METHOD_DELETE])]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: 'Product deleted'
    )]
    public function deleteAction(int $id, ProductRepository $productRepository): JsonResponse
    {
        $product = $productRepository->find($id);
        if (is_null($product)) {
            return $this->json([], Response::HTTP_NOT_FOUND);
        }
        $productRepository->remove($product);
        return $this->json([], Response::HTTP_OK);
    }
}
