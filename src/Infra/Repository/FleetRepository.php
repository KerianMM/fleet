<?php declare(strict_types = 1);

namespace Kerianmm\Fleet\Infra\Repository;

use Kerianmm\Fleet\Domain\Exception\FleetNotFound;
use Kerianmm\Fleet\Domain\Model\Fleet;
use Kerianmm\Fleet\Domain\Repository\FleetRepositoryInterface;

final class FleetRepository implements FleetRepositoryInterface
{
    public function __construct(private array $fleets = [])
    {
    }

    public function save(Fleet $fleet): void
    {
        $this->fleets[$fleet->id] = $fleet;
    }

    /**
     * @inheritDoc
     */
    public function find(string $id): Fleet
    {
        return $this->fleets[$id] ?? throw new FleetNotFound($id);
    }
}