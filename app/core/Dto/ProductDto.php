<?php

declare(strict_types=1);

namespace Dto;

class ProductDto
{
    public function __construct(private string $id, private float $price, private float $priceOnSale, private int $active, private int $activeOnSale, private int $personalized, private \DateTimeImmutable $createdAt, private \DateTimeImmutable $updatedAt, private ?int $skuNumber, private int $softDeleted, private int $featured, private int $popularity, private string $productId, private string $locale, private string $name, private string $image)
    {
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * @return float
     */
    public function getPriceOnSale(): float
    {
        return $this->priceOnSale;
    }

    /**
     * @return int
     */
    public function getActive(): int
    {
        return $this->active;
    }

    /**
     * @return int
     */
    public function getActiveOnSale(): int
    {
        return $this->activeOnSale;
    }

    /**
     * @return int
     */
    public function getPersonalized(): int
    {
        return $this->personalized;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getUpdatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }

    /**
     * @return int|null
     */
    public function getSkuNumber(): ?int
    {
        return $this->skuNumber;
    }

    /**
     * @return int
     */
    public function getSoftDeleted(): int
    {
        return $this->softDeleted;
    }

    /**
     * @return int
     */
    public function getFeatured(): int
    {
        return $this->featured;
    }

    /**
     * @return int
     */
    public function getPopularity(): int
    {
        return $this->popularity;
    }

    /**
     * @return string
     */
    public function getProductId(): string
    {
        return $this->productId;
    }

    /**
     * @return string
     */
    public function getLocale(): string
    {
        return $this->locale;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getImage(): string
    {
        return $this->image;
    }
}