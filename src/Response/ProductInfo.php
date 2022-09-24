<?php

namespace App\Response;

use App\Entity\Product;
use DateTimeImmutable;

class ProductInfo
{
    private ?int $id;
    private ?string $name;
    private ?string $description;
    private ?float $price;
    private ?DateTimeImmutable $createdAt;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     * @return ProductInfo
     */
    public function setId(?int $id): ProductInfo
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     * @return ProductInfo
     */
    public function setName(?string $name): ProductInfo
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
     * @return ProductInfo
     */
    public function setDescription(?string $description): ProductInfo
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
     * @return ProductInfo
     */
    public function setPrice(?float $price): ProductInfo
    {
        $this->price = $price;
        return $this;
    }

    /**
     * @return DateTimeImmutable|null
     */
    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @param DateTimeImmutable|null $createdAt
     * @return ProductInfo
     */
    public function setCreatedAt(?DateTimeImmutable $createdAt): ProductInfo
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public static function getInstance(Product $product): self
    {
        $item = new self();
        return $item->setDescription($product->getDescription())
            ->setCreatedAt($product->getCreatedAt())
            ->setPrice($product->getPrice())
            ->setName($product->getName())
            ->setId($product->getId())
        ;
    }
}