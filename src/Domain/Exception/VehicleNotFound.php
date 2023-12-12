<?php declare(strict_types = 1);

namespace Kerianmm\Fleet\Domain\Exception;

final class VehicleNotFound extends \DomainException
{
    public function __construct(string $plateNumber)
    {
        parent::__construct(sprintf('No vehicle found with plate number "%s"', $plateNumber));
    }
}