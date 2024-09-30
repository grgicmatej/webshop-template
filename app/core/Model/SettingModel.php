<?php

declare(strict_types=1);

namespace Model;

class SettingModel
{
    public function __construct(private string $id, private string $settingKey, private string $value)
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
    public function getSettingKey(): string
    {
        return $this->settingKey;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }
}
