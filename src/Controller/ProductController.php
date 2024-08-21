<?php

namespace App\Controller;

use App\Entity\Product;
use App\Service\ProductService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * The ProductController class is responsible for handling requests related to products.
 * It extends the AbstractController class and is used to define routes and actions.
 */
class ProductController extends AbstractController
{

    /**
     * This constructor is used to inject dependencies into the ProductController class.
     * We pass the ProductService class as a dependency to the constructor in order to
     * avoid using repository methods directly in the controller.
     */
    public function __construct(
        private ProductService $service,
    ) {}

    // This route is used to retrieve all products using Route annotation
    #[Route('/products', name: 'products', methods: ['GET', 'HEAD'])]
    public function index(): JsonResponse
    {
        // We call the products() method on the ProductService class to retrieve all products
        return new JsonResponse($this->service->products());
    }

    /**
     * This route is used to retrieve a single product and also using EntityValueResolver
     * We don't need to use a repository to retrieve a single product instead we use
     * EntityValueResolver to directly retrieve the product from the database.
     */
    #[Route('/products/{id}', name: 'products_single', methods: ['GET'])]
    public function show(Product $product): JsonResponse
    {
        // We call the toJson() method on the Product entity to retrieve the product data in JSON format
        return new JsonResponse($product->toJson());
    }

    /**
     * This route is used to create a new product. We use the create() method on the ProductService class
     * to create a new product and return the product data in JSON format.
     * @throws \InvalidArgumentException
     */
    #[Route('/products', name: 'products_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        return new JsonResponse($this->service->create($request));
    }

    /**
     * This route is used to update an existing product. We use the update() method on the ProductService class
     * to update the product and return the product data in JSON format. Also, we use the EntityValueResolver
     * to directly update the product in the database.
     * @throws \InvalidArgumentException
     */
    #[Route('/products/{id}', name: 'products_update', methods: ['PUT'])]
    public function update(Request $request, Product $product): JsonResponse
    {
        return new JsonResponse($this->service->update($request, $product));
    }

    /**
     * This route is used to delete a product. We use the delete() method on the ProductService class
     * to delete the product from the database. We also return a JSON response with a message.
     * We use the EntityValueResolver to directly delete the product from the database.
     */
    #[Route('/products/{id}', name: 'products_delete', methods: ['DELETE'])]
    public function delete(Product $product): JsonResponse
    {
        $this->service->delete($product);
        return new JsonResponse(['message' => 'Product deleted'], Response::HTTP_NO_CONTENT);
    }

}