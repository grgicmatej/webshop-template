<?php

declare(strict_types=1);

namespace Dto;

use Model\ColorModel;
use Model\OrderModel;
use Model\ProductImageModel;
use Model\ProductModel;
use Model\ProductNameTranslationModel;
use Model\SizeModel;
use Model\UserModel;

class ShoppingCartDto
{
    public function __construct(private string $id, private OrderModel $order, private ProductModel $product, private float $productPrice, private ColorModel $color, private SizeModel $size, private \DateTimeImmutable $validUntil, private UserModel $user, private \DateTimeImmutable $createdAt, private bool $orderCompleted, private ProductNameTranslationModel $productNameTranslation, private ProductImageModel $productImage)
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
     * @return OrderModel
     */
    public function getOrder(): OrderModel
    {
        return $this->order;
    }

    /**
     * @return ProductModel
     */
    public function getProduct(): ProductModel
    {
        return $this->product;
    }

    /**
     * @return float
     */
    public function getProductPrice(): float
    {
        return $this->productPrice;
    }

    /**
     * @return ColorModel
     */
    public function getColor(): ColorModel
    {
        return $this->color;
    }

    /**
     * @return SizeModel
     */
    public function getSize(): SizeModel
    {
        return $this->size;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getValidUntil(): \DateTimeImmutable
    {
        return $this->validUntil;
    }

    /**
     * @return UserModel
     */
    public function getUser(): UserModel
    {
        return $this->user;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @return bool
     */
    public function isOrderCompleted(): bool
    {
        return $this->orderCompleted;
    }

    /**
     * @return ProductNameTranslationModel
     */
    public function getProductNameTranslation(): ProductNameTranslationModel
    {
        return $this->productNameTranslation;
    }

    /**
     * @return ProductImageModel
     */
    public function getProductImage(): ProductImageModel
    {
        return $this->productImage;
    }
}