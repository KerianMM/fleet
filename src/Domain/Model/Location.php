<?php declare(strict_types = 1);

namespace Kerianmm\Fleet\Domain\Model;

final readonly class Location
{
    public function __construct(
        public float $latitude,
        public float $longitude,
    ) {
    }
}