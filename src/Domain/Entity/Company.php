<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\Country;
use App\Domain\Feature\CompanyCreation\DTO\Company as CreateCompanyDto;
use App\Domain\Feature\CompanyCreation\DTO\Employee as EmployeeDto;
use App\Domain\Feature\CompanyUpdate\DTO\Company as UpdateCompanyDto;
use App\Domain\ValueObject\BaseString;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Ulid;

#[ORM\Entity]
#[ORM\Table(name: 'companies')]
#[ORM\UniqueConstraint(name: 'country_taxid_unique', columns: ['country', 'tax_id'])]
class Company
{
    #[ORM\Id]
    #[ORM\Column(type: 'ulid', unique: true)]
    private Ulid $id;

    /**
     * @var Collection<int, Employee>
     */
    #[ORM\OneToMany(targetEntity: Employee::class, mappedBy: 'company', cascade: ['persist', 'remove'])]
    private Collection $employees;

    #[ORM\Column(type: 'string')]
    private string $name;

    #[ORM\Column(type: 'string')]
    private string $taxId;

    #[ORM\Column(length: 2, enumType: Country::class)]
    private Country $country;

    #[ORM\Column(type: 'string')]
    private string $address;

    #[ORM\Column(type: 'string')]
    private string $postalCode;

    #[ORM\Column(type: 'string')]
    private string $city;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    public function __construct(
        Ulid $id,
        BaseString $name,
        BaseString $taxId,
        BaseString $address,
        BaseString $postalCode,
        BaseString $city,
        \DateTimeImmutable $createdAt,
    ) {
        $this->id = $id;
        $this->name = $name->value;
        $this->taxId = $taxId->value;
        $this->country = Country::PL;
        $this->address = $address->value;
        $this->postalCode = $postalCode->value;
        $this->city = $city->value;
        $this->createdAt = $createdAt;
        $this->employees = new ArrayCollection();
    }

    public function assignEmployeesFromDtos(EmployeeDto ...$employees): void
    {
        foreach ($employees as $employee) {
            $this->employees->add(new Employee(
                $employee->id,
                $employee->firstName,
                $employee->lastName,
                $employee->email,
                $employee->phone,
                $employee->createdAt,
                $this,
            ));
        }
    }

    public static function createFromDto(CreateCompanyDto $companyDto): self
    {
        $company = new self(
            $companyDto->id,
            $companyDto->name,
            $companyDto->taxId,
            $companyDto->address,
            $companyDto->postalCode,
            $companyDto->city,
            $companyDto->createdAt
        );

        $company->assignEmployeesFromDtos(...$companyDto->getEmployees());

        return $company;
    }

    public function updateFromDto(UpdateCompanyDto $companyDto): void
    {
        $this->name = $companyDto->name->value;
        $this->taxId = $companyDto->taxId->value;
        $this->address = $companyDto->address->value;
        $this->postalCode = $companyDto->postalCode->value;
        $this->city = $companyDto->city->value;
    }

    public function getId(): Ulid
    {
        return $this->id;
    }

    public function getName(): BaseString
    {
        return new BaseString($this->name);
    }

    public function getTaxId(): BaseString
    {
        return new BaseString($this->taxId);
    }

    public function getAddress(): BaseString
    {
        return new BaseString($this->address);
    }

    public function getCity(): BaseString
    {
        return new BaseString($this->city);
    }

    public function getPostalCode(): BaseString
    {
        return new BaseString($this->postalCode);
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @return array<Employee>
     */
    public function getEmployees(): array
    {
        return $this->employees->toArray();
    }
}
