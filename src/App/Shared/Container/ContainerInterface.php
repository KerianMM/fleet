<?php declare(strict_types = 1);

namespace Kerianmm\Fleet\App\Shared\Container;

interface ContainerInterface
{
    public function set(string $id, object $service): static;
    public function get(string $id): ?object;
}