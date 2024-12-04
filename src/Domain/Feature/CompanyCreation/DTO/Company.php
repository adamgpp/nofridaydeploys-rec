<?php

declare(strict_types=1);

namespace App\Domain\Feature\CompanyCreation\DTO;

use App\Domain\ValueObject\BaseString;
use Symfony\Component\Uid\Ulid;

final readonly class Company
{
    /**
     * @var array<Employee>
     */
    private array $employees;

    private function __construct(
        public Ulid $id,
        public BaseString $name,
        public BaseString $taxId,
        public BaseString $address,
        public BaseString $postalCode,
        public BaseString $city,
        public \DateTimeImmutable $createdAt,
        Employee ...$employees,
    ) {
        $this->employees = $employees;
    }

    public static function fromArray(Ulid $id, \DateTimeImmutable $createdAt, array $array): self
    {
        $employees = array_map(static fn (array $employee) => Employee::fromArray(
            new Ulid(),
            $createdAt,
            $employee
        ), $array['employees']);

        return new self(
            $id,
            new BaseString($array['companyName']),
            new BaseString($array['companyTaxId']),
            new BaseString($array['companyAddress']),
            new BaseString($array['companyPostalCode']),
            new BaseString($array['companyCity']),
            $createdAt,
            ...$employees
        );
    }

    /**
     * @return array<Employee>
     */
    public function getEmployees(): array
    {
        return $this->employees;
    }
}
