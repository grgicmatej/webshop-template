<?php

declare(strict_types=1);

namespace Enum;

class SettingKeyEnum
{
    private const CONTACT_EMAIL_KEY = 'Email za kontakte';
    private const ORDER_EMAIL_KEY = 'Email za narudžbe';
    private const DELIVERY_COST_KEY = 'Trošak dostave';
    private const DELIVERY_FREE_KEY = 'Besplatna dostava iznad iznosa:';
    private const ACTIVE_DELIVERY_FREE_KEY = 'Aktivna besplatna dostava';

    private const IBAN_KEY = 'IBAN';
    private const ENABLED_FREE_DELIVERY = 'da';
    private const DISABLED_FREE_DELIVERY = 'ne';

    public static function getContactEmailKey(): string
    {
        return self::CONTACT_EMAIL_KEY;
    }

    public static function getOrderEmailKey(): string
    {
        return self::ORDER_EMAIL_KEY;
    }

    public static function getDeliveryCostKey(): string
    {
        return self::DELIVERY_COST_KEY;
    }

    public static function getDeliveryFreeKey(): string
    {
        return self::DELIVERY_FREE_KEY;
    }

    public static function getActiveDeliveryFreeKey(): string
    {
        return self::ACTIVE_DELIVERY_FREE_KEY;
    }

    public static function getEnabledFreeDelivery(): string
    {
        return self::ENABLED_FREE_DELIVERY;
    }

    public static function getDisabledFreeDelivery(): string
    {
        return self::DISABLED_FREE_DELIVERY;
    }

    public static function getIbanKey(): string
    {
        return self::IBAN_KEY;
    }
}
