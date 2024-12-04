<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Country;
use App\Domain\Entity\Company;
use App\Domain\Repository\CompanyRepositoryInterface;
use App\Domain\ValueObject\BaseString;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Uid\Ulid;

final readonly class DoctrineCompanyRepository implements CompanyRepositoryInterface
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function existsById(Ulid $id): bool
    {
        return $this->entityManager->find(Company::class, $id) instanceof Company;
    }

    public function findById(Ulid $id): ?Company
    {
        return $this->entityManager->find(Company::class, $id);
    }

    public function existsByTaxId(Country $country, BaseString $taxId): bool
    {
        $company = $this->entityManager
            ->createQueryBuilder()
            ->select('c')
            ->from(Company::class, 'c')
            ->where('c.country = :country')
            ->andWhere('c.taxId = :taxId')
            ->setParameter(':country', $country->value)
            ->setParameter(':taxId', $taxId->value)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        return $company instanceof Company;
    }

    public function findByTaxId(Country $country, BaseString $taxId): ?Company
    {
        return $this->entityManager
            ->createQueryBuilder()
            ->select('c')
            ->from(Company::class, 'c')
            ->where('c.country = :country')
            ->andWhere('c.taxId = :taxId')
            ->setParameter(':country', $country->value)
            ->setParameter(':taxId', $taxId->value)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function add(Company $company): void
    {
        $this->entityManager->persist($company);
    }

    public function delete(Company $company): void
    {
        $this->entityManager->remove($company);
    }

    public function confirm(): void
    {
        $this->entityManager->flush();
    }
}
