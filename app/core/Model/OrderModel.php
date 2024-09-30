<?php

declare(strict_types=1);

namespace Model;

class OrderModel
{
    public function __construct(private int $id, private UserModel $user, private string $orderId, private \DateTimeImmutable $createdAt, private float $productPrice, private float $deliveryPrice, private float $totalPrice, private string $paymentMethod, private string $status, private string $comment)
    {
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return UserModel
     */
    public function getUser(): UserModel
    {
        return $this->user;
    }

    /**
     * @return string
     */
    public function getOrderId(): string
    {
        return $this->orderId;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @return float
     */
    public function getProductPrice(): float
    {
        return $this->productPrice;
    }

    /**
     * @return float
     */
    public function getDeliveryPrice(): float
    {
        return $this->deliveryPrice;
    }

    /**
     * @return float
     */
    public function getTotalPrice(): float
    {
        return $this->totalPrice;
    }

    /**
     * @return string
     */
    public function getPaymentMethod(): string
    {
        return $this->paymentMethod;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function getComment(): string
    {
        return $this->comment;
    }
}
