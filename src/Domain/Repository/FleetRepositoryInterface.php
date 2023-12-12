<?php declare(strict_types = 1);

namespace Kerianmm\Fleet\Domain\Repository;

use Kerianmm\Fleet\Domain\Exception\FleetNotFound;
use Kerianmm\Fleet\Domain\Model\Fleet;

interface FleetRepositoryInterface
{
    public function save(Fleet $fleet): void;

    /**
     * @throws FleetNotFound
     */
    public function find(string $id): Fleet;
}