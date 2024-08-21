<?php

namespace App\Service;

use App\DTO\ProductDTO;
use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ProductService
{

    /**
     * This constructor is used to inject dependencies into the ProductService class.
     * ProductRepository is used to interact with the database using Doctrine.
     * EntityManagerInterface is used to persist and flush the data to the database.
     * ValidatorInterface is used to validate the request data.
     */
    public function __construct(
        private ProductRepository $productRepository,
        private EntityManagerInterface $entityManager,
        private ValidatorInterface $validator
    ) {}

    /**
     * This method returns an array of all products in the database
     * and mapped to a JSON format for the response.
     */
    public function products(): array
    {
        return array_map(fn(Product $product) => $product->toJson(),
            $this->productRepository->findAll()
        );
    }

    /**
     * This method creates a new product in the database.
     * It validates the request data and saves it to the database.
     * If the validation fails, it throws an exception.
     * @throws \InvalidArgumentException
     */
    public function create(Request $request): array
    {
        $product = $this->save(new Product(), $this->validated($request));

        $this->entityManager->persist($product);
        $this->entityManager->flush();

        return $product->toJson();
    }

    /**
     * This method updates an existing product in the database.
     * It validates the request data and saves it to the database.
     * If the validation fails, it throws an exception.
     * @throws \InvalidArgumentException
     */
    public function update(Request $request, Product $product): array
    {
        $product = $this->save($product, $this->validated($request));
        $this->entityManager->flush();

        return $product->toJson();
    }

    /**
     * This method validates the request data and returns a ProductDTO object.
     * If the validation fails, it loops through the errors and throws an exception.
     * The method is used to validate the request data before saving it to the database.
     * @throws \InvalidArgumentException
     */
    private function validated(Request $request): ProductDTO
    {
        $data = json_decode($request->getContent(), true);

        $productDTO = new ProductDTO();
        $productDTO->name = $data['name'] ?? null;
        $productDTO->price = $data['price'] ?? null;
        $productDTO->description = $data['description'] ?? null;

        $errors = $this->validator->validate($productDTO);

        if ($errors->count() > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }

            throw new \InvalidArgumentException(implode("\n", $errorMessages));
        }

        return $productDTO;
    }

    /**
     * This method initializes either new or existing Product entity
     * with the validated ProductDTO data and returns it.
     */
    private function save(Product $product, ProductDTO $productDTO): Product
    {
        $product->setName($productDTO->name);
        $product->setPrice($productDTO->price);
        $product->setDescription($productDTO->description);
        return $product;
    }

    /**
     * This method deletes a product from the database.
     */
    public function delete(Product $product): void
    {
        $this->entityManager->remove($product);
        $this->entityManager->flush();
    }

}