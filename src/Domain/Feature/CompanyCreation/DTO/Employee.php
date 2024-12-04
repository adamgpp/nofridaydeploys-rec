<?php

declare(strict_types=1);

namespace App\Domain\Feature\CompanyCreation\DTO;

use App\Domain\ValueObject\BaseString;
use App\Domain\ValueObject\Email;
use Symfony\Component\Uid\Ulid;

final readonly class Employee
{
    private function __construct(
        public Ulid $id,
        public BaseString $firstName,
        public BaseString $lastName,
        public Email $email,
        public ?BaseString $phone,
        public \DateTimeImmutable $createdAt,
    ) {
    }

    public static function fromArray(Ulid $id, \DateTimeImmutable $createdAt, array $array): self
    {
        return new self(
            $id,
            new BaseString($array['employeeFirstName']),
            new BaseString($array['employeeLastName']),
            new Email($array['employeeEmail']),
            isset($array['employeePhone']) ? new BaseString($array['employeePhone']) : null,
            $createdAt,
        );
    }
}
