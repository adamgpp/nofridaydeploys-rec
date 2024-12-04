<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\ValueObject\BaseString;
use App\Domain\ValueObject\Email;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Ulid;

#[ORM\Entity]
#[ORM\Table(
    name: 'employees'
)]
class Employee
{
    #[ORM\Id]
    #[ORM\Column(type: 'ulid', unique: true)]
    private Ulid $id;

    #[ORM\Column(type: 'string')]
    private string $firstName;

    #[ORM\Column(type: 'string')]
    private string $lastName;

    #[ORM\Column(type: 'string')]
    private string $email;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $phone;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    #[ORM\ManyToOne(targetEntity: Company::class, inversedBy: 'employees')]
    #[ORM\JoinColumn(nullable: false)]
    private Company $company;

    public function __construct(
        Ulid $id,
        BaseString $firstName,
        BaseString $lastName,
        Email $email,
        ?BaseString $phone,
        \DateTimeImmutable $createdAt,
        Company $company,
    ) {
        $this->id = $id;
        $this->firstName = $firstName->value;
        $this->lastName = $lastName->value;
        $this->email = $email->value;
        $this->phone = $phone?->value;
        $this->createdAt = $createdAt;
        $this->company = $company;
    }

    public function getId(): Ulid
    {
        return $this->id;
    }

    public function getFirstName(): BaseString
    {
        return new BaseString($this->firstName);
    }
}
