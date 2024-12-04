<?php

declare(strict_types=1);

namespace App\Application\Query\Repository;

use Symfony\Component\Uid\Ulid;

interface CompanyRepositoryInterface
{
    public function findById(Ulid $id): ?array;

    /**
     * @return array<array>
     */
    public function findAll(): array;
}
