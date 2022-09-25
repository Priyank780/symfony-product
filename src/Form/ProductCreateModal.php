<?php

namespace App\Form;

use Symfony\Component\Validator\Constraints as Assert;

class ProductCreateModal
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

    public function setName(?string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(?float $price): self
    {
        $this->price = $price;
        return $this;
    }
}