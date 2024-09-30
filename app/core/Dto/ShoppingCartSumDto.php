<?php

declare(strict_types=1);

namespace Dto;

class ShoppingCartSumDto
{
    public function __construct(private string $sum)
    {
    }

    /**
     * @return string
     */
    public function getSum(): string
    {
        return $this->sum;
    }
}
