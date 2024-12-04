<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Country;
use App\Domain\Entity\Company;
use App\Domain\ValueObject\BaseString;
use Symfony\Component\Uid\Ulid;

interface CompanyRepositoryInterface
{
    public function existsById(Ulid $id): bool;

    public function findById(Ulid $id): ?Company;

    public function existsByTaxId(Country $country, BaseString $taxId): bool;

    public function findByTaxId(Country $country, BaseString $taxId): ?Company;

    public function add(Company $company): void;

    public function delete(Company $company): void;

    public function confirm(): void;
}
