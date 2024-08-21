<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class ProductDTO
{

    #[Assert\NotBlank]
    #[Assert\Length(min: 1, max: 255)]
    #[Assert\Type("string")]
    public string $name;

    #[Assert\NotBlank]
    #[Assert\Type("integer")]
    public int $price;

    #[Assert\Length(min: 0, max: 255)]
    #[Assert\Type("string")]
    public ?string $description = null;
}