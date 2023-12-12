<?php declare(strict_types = 1);

namespace Kerianmm\Fleet\App\Command;

use Kerianmm\Fleet\App\Shared\Command\CommandInterface;

final readonly class ParkCommand implements CommandInterface
{
    public function __construct(
        public string $fleetId, // useless ?
        public string $plateNumber,
        public float $latitude,
        public float $longitude,
    ){
    }
}