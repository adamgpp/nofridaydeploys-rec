<?php

declare(strict_types=1);

namespace App\Domain\Feature\CompanyCreation\Validation;

use App\Domain\ValueObject\BaseString;
use Symfony\Component\Uid\Ulid;

interface CompanyCreationValidationInterface
{
    public function assertCompanyCanBeCreated(Ulid $id, BaseString $taxId): void;
}
