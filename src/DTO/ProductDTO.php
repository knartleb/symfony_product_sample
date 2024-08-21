<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * The ProductDTO class is used to validate and map the request data
 * to the Product entity. It is used to create, update, and delete products.
 * @see ProductService
 */
class ProductDTO
{

    #[Assert\NotBlank] // This property is used to validate the name of the product is not empty
    #[Assert\Length(min: 1, max: 255)] // This property is used to validate the length of the name is between 1 and 255 characters
    #[Assert\Type("string")] // This property is used to validate the type of the name is a string
    public string $name;

    #[Assert\NotBlank]
    #[Assert\Type("integer")] // This property is used to validate the type of the price is an integer
    public int $price;

    #[Assert\Length(min: 0, max: 255)]
    #[Assert\Type("string")]
    public ?string $description = null;
}