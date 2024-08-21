<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id] // This property is used to uniquely identify each product
    #[ORM\GeneratedValue] // This property is used to automatically generate a unique ID for each product
    #[ORM\Column] // This property is used to map the ID column in the database
    private ?int $id = null;

    #[ORM\Column(length: 255)] // This property is used to store the name of the product
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)] // This property is used to store the description of the product
    private ?string $description = null;

    #[ORM\Column] // This property is used to store the price of the product
    private ?int $price = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): static
    {
        $this->price = $price;

        return $this;
    }

    /**
     * This method returns an array of the Product entity's properties
     * in a JSON format for the response.
     */
    public function toJson(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'description' => $this->getDescription(),
            'price' => $this->getPrice(),
        ];
    }
}
