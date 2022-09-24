<?php
namespace App\Request;

use Symfony\Component\Validator\Constraints as Assert;

class ProductCreateRequest
{
    #[Assert\NotNull(message: 'api.1.0.not_null')]
    #[Assert\NotBlank(message: 'api.1.0.not_blank')]
    private ?string $name;
    #[Assert\NotNull(message: 'api.1.0.not_null')]
    #[Assert\NotBlank(message: 'api.1.0.not_blank')]
    private ?string $description;
    #[Assert\NotNull(message: 'api.1.0.not_null')]
    #[Assert\NotBlank(message: 'api.1.0.not_blank')]
    #[Assert\Range(min: 1, max: 10000)]
    private ?float $price;

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     * @return ProductCreateRequest
     */
    public function setName(?string $name): ProductCreateRequest
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     * @return ProductCreateRequest
     */
    public function setDescription(?string $description): ProductCreateRequest
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getPrice(): ?float
    {
        return $this->price;
    }

    /**
     * @param float|null $price
     * @return ProductCreateRequest
     */
    public function setPrice(?float $price): ProductCreateRequest
    {
        $this->price = $price;
        return $this;
    }
}