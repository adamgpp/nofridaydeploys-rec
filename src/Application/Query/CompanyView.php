<?php

declare(strict_types=1);

namespace App\Application\Query;

use Symfony\Component\Uid\Ulid;

final readonly class CompanyView
{
    private function __construct(
        public string $id,
        public string $name,
        public string $taxId,
        public string $address,
        public string $postalCode,
        public string $city,
        public string $createdAt,
        public array $employees,
    ) {
    }

    public static function fromDatabaseData(array $array): self
    {
        return new self(
            Ulid::fromBinary($array['id'])->toBase32(),
            $array['name'],
            $array['tax_id'],
            $array['address'],
            $array['postal_code'],
            $array['city'],
            $array['created_at'],
            array_map(static fn (array $employee) => [
                'id' => Ulid::fromBinary($employee['id'])->toBase32(),
                'firstName' => $employee['first_name'],
                'lastName' => $employee['last_name'],
                'email' => $employee['email'],
                'phone' => $employee['phone'],
                'createdAt' => $employee['created_at'],
            ], $array['employees']),
        );
    }
}
