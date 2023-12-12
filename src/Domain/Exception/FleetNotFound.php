<?php declare(strict_types = 1);

namespace Kerianmm\Fleet\Domain\Exception;

final class FleetNotFound extends \DomainException
{
    public function __construct(string $id)
    {
        parent::__construct(sprintf('No fleet found with id "%s"', $id));
    }
}