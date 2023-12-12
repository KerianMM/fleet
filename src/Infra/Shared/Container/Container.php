<?php declare(strict_types = 1);

namespace Kerianmm\Fleet\Infra\Shared\Container;

use Kerianmm\Fleet\App\Shared\Container\ContainerInterface;

final class Container implements ContainerInterface
{
    public function __construct(
        private array $services,
    ) {
    }

    public function set(string $id, object $service): static
    {
        $this->services[$id] = $service;

        return $this;
    }

    public function get(string $id): ?object
    {
        return $this->services[$id] ?? null;
    }
}