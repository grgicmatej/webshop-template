<?php

declare(strict_types=1);

namespace Enum;

class OrdersEnum
{
    private const RECEIVED = 'Zaprimljeno';
    private const SENT = 'Poslano';
    private const ON_HOLD = 'Na čekanju';
    private const CANCELED = 'Otkazano';
    private const FINISHED = 'Završeno';

    private const PICKUP = 'Osobno preuzimanje - Samobor';

    private const PAYMENT = 'Racun';

    public static function getReceived(): string
    {
        return self::RECEIVED;
    }

    public static function getSent(): string
    {
        return self::SENT;
    }

    public static function getOnHold(): string
    {
        return self::ON_HOLD;
    }

    public static function getCanceled(): string
    {
        return self::CANCELED;
    }

    public static function getFinished(): string
    {
        return self::FINISHED;
    }

    public static function getStatusAsArray(): array
    {
        return [self::RECEIVED, self::SENT, self::ON_HOLD, self::CANCELED, self::FINISHED];
    }

    public static function getPickup(): string
    {
        return self::PICKUP;
    }

    public static function getPayment(): string
    {
        return self::PAYMENT;
    }
}