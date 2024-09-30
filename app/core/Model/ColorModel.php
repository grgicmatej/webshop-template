<?php

declare(strict_types=1);

namespace Model;

class ColorModel
{

    public function __construct(private string $id, private string $name)
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
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
