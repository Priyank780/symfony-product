<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductCreateModal;
use App\Form\ProductUpdateModal;
use App\Form\Type\ProductCreateType;
use App\Form\Type\ProductUpdateType;
use App\Repository\ProductRepository;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    #[Route('/products', name: 'app_products')]
    public function index(ProductRepository $productRepository): Response
    {
        $list = $productRepository->findAll();
        return $this->render('list.html.twig', ['list' => $list]);
    }
    #[Route('/products/create', name: 'app_products_create', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function create(Request $request, ProductRepository $productRepository): Response
    {
        $model = new ProductCreateModal();
        $form = $this->createForm(ProductCreateType::class, $model, [
            'action' => $this->generateUrl('app_products_create'),
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $product = new Product();
            $product
                ->setName($model->getName())
                ->setDescription($model->getDescription())
                ->setPrice($model->getPrice())
                ->setCreatedAt(new DateTimeImmutable())
            ;
            $productRepository->save($product, true);
            return $this->redirectToRoute('app_products');
        }
        return $this->render('create.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/products/{id}', name: 'app_products_edit', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function edit(Request $request, int $id, ProductRepository $productRepository): Response
    {
        $product = $productRepository->find($id);
        $model = new ProductUpdateModal();
        $model
            ->setName($product->getName())
            ->setDescription($product->getDescription())
            ->setPrice($product->getPrice())
        ;
        $form = $this->createForm(ProductUpdateType::class, $model, [
            'action' => $this->generateUrl('app_products_edit', ['id' => $id]),
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $product
                ->setName($model->getName())
                ->setDescription($model->getDescription())
                ->setPrice($model->getPrice())
            ;
            $productRepository->save($product, true);
            return $this->redirectToRoute('app_products');
        }
        return $this->render('edit.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/products/{id}/delete', name: 'app_products_delete', methods: [Request::METHOD_POST])]
    public function deleteAction(int $id, ProductRepository $productRepository): Response
    {
        $product = $productRepository->find($id);
        $productRepository->remove($product, true);
        return $this->redirectToRoute('app_products');
    }
}
