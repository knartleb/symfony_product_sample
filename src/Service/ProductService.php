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

    public function __construct(
        private ProductRepository $productRepository,
        private EntityManagerInterface $entityManager,
        private ValidatorInterface $validator
    ) {}

    public function products(): array
    {
        return array_map(fn(Product $product) => $product->toJson(),
            $this->productRepository->findAll()
        );
    }

    /**
     * @throws \InvalidArgumentException
     */
    public function create(Request $request): array
    {
        $product = $this->save(new Product(), $this->validated($request));

        $this->entityManager->persist($product);
        $this->entityManager->flush();

        return $product->toJson();
    }

    public function update(Request $request, Product $product): array
    {
        $product = $this->save($product, $this->validated($request));
        $this->entityManager->flush();

        return $product->toJson();
    }

    /**
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

    private function save(Product $product, ProductDTO $productDTO): Product
    {
        $product->setName($productDTO->name);
        $product->setPrice($productDTO->price);
        $product->setDescription($productDTO->description);
        return $product;
    }

    public function delete(Product $product): void
    {
        $this->entityManager->remove($product);
        $this->entityManager->flush();
    }

}