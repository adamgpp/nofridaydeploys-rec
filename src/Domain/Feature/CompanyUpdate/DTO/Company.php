<?php

declare(strict_types=1);

namespace App\Domain\Feature\CompanyUpdate\DTO;

use App\Domain\ValueObject\BaseString;
use Symfony\Component\Uid\Ulid;

final readonly class Company
{
    private function __construct(
        public Ulid $id,
        public BaseString $name,
        public BaseString $taxId,
        public BaseString $address,
        public BaseString $postalCode,
        public BaseString $city,
    ) {
    }

    public static function fromArray(Ulid $id, array $array): self
    {
        return new self(
            $id,
            new BaseString($array['companyName']),
            new BaseString($array['companyTaxId']),
            new BaseString($array['companyAddress']),
            new BaseString($array['companyPostalCode']),
            new BaseString($array['companyCity']),
        );
    }
}
