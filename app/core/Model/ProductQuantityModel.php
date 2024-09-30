<?php

declare(strict_types=1);

namespace Model;

class ProductQuantityModel
{

    public function __construct(private string $id, private ProductModel $product, private ColorModel $color, private SizeModel $size, private int $available, private int $reserved, private int $sold)
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
     * @return ProductModel
     */
    public function getProduct(): ProductModel
    {
        return $this->product;
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
     * @return int
     */
    public function getAvailable(): int
    {
        return $this->available;
    }

    /**
     * @return int
     */
    public function getReserved(): int
    {
        return $this->reserved;
    }

    /**
     * @return int
     */
    public function getSold(): int
    {
        return $this->sold;
    }
}
