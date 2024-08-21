<?php

namespace App\Controller;

use App\DTO\ProductDTO;
use App\Entity\Product;
use App\Service\ProductService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ProductController extends AbstractController
{

    public function __construct(
        private ProductService $service,
    ) {}

    #[Route('/products', name: 'products', methods: ['GET', 'HEAD'])]
    public function index(): JsonResponse
    {
        return new JsonResponse($this->service->products());
    }

    #[Route('/products/{id}', name: 'products_single', methods: ['GET'])]
    public function show(Product $product): JsonResponse
    {
        return new JsonResponse($product->toJson());
    }

    /**
     * @throws \InvalidArgumentException
     */
    #[Route('/products', name: 'products_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        return new JsonResponse($this->service->create($request));
    }

    /**
     * @throws \InvalidArgumentException
     */
    #[Route('/products/{id}', name: 'products_update', methods: ['PUT'])]
    public function update(Request $request, Product $product): JsonResponse
    {
        return new JsonResponse($this->service->update($request, $product));
    }

    #[Route('/products/{id}', name: 'products_delete', methods: ['DELETE'])]
    public function delete(Product $product): JsonResponse
    {
        $this->service->delete($product);
        return new JsonResponse(['message' => 'Product deleted'], Response::HTTP_NO_CONTENT);
    }

}