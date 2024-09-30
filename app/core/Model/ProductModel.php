<?php

declare(strict_types=1);

namespace Model;

class ProductModel
{
    public function __construct(private string $id, private float $price, private ?float $priceOnSale, private int $active, private int $activeSalePrice, private int $personalized, private \DateTimeImmutable $createdAt, private \DateTimeImmutable $updatedAt, private ?int $skuNumber, private int $softDeleted, private int $featured, private int $popularity)
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
     * @return float|null
     */
    public function getPriceOnSale(): ?float
    {
        return $this->priceOnSale;
    }

    /**
     * @return int
     */
    public function isActive(): int
    {
        return $this->active;
    }

    /**
     * @return int
     */
    public function isActiveSalePrice(): int
    {
        return $this->activeSalePrice;
    }

    /**
     * @return int
     */
    public function isPersonalized(): int
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
     * @return ?int
     */
    public function getSkuNumber(): ?int
    {
        return $this->skuNumber;
    }

    /**
     * @return int
     */
    public function isSoftDeleted(): int
    {
        return $this->softDeleted;
    }

    /**
     * @return int
     */
    public function isFeatured(): int
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
}
