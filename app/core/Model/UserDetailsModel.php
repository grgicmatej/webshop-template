<?php

declare(strict_types=1);

namespace Model;

class UserDetailsModel
{
    public function __construct(private UserModel $users, private ?string $name, private ?string $surname, private ?string $address, private ?string $city, private ?string $postal, private ?string $phone, private bool $guest)
    {
    }

    /**
     * @return UserModel
     */
    public function getUsers(): UserModel
    {
        return $this->users;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getSurname(): ?string
    {
        return $this->surname;
    }

    /**
     * @return string|null
     */
    public function getAddress(): ?string
    {
        return $this->address;
    }

    /**
     * @return string|null
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * @return string|null
     */
    public function getPostal(): ?string
    {
        return $this->postal;
    }

    /**
     * @return string|null
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * @return bool
     */
    public function isGuest(): bool
    {
        return $this->guest;
    }
}
