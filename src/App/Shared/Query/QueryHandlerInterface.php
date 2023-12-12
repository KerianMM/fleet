<?php declare(strict_types = 1);

namespace Kerianmm\Fleet\App\Shared\Query;

interface QueryHandlerInterface
{
    public function __invoke(QueryInterface $query): mixed;
}