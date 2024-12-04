<?php

declare(strict_types=1);

namespace App\Domain\Feature\CompanyDeletion;

use Symfony\Component\Uid\Ulid;

interface CompanyDeletionInterface
{
    public function deleteCompany(Ulid $id): void;
}
