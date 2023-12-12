<?php declare(strict_types = 1);

namespace Kerianmm\Fleet\App\Query;

use Kerianmm\Fleet\App\Shared;

final readonly class LocationQuery implements Shared\Query\QueryInterface
{
    public function __construct(
        public string $fleetId,
        public string $plateNumber,
    ) {
    }
}