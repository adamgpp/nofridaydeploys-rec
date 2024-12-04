<?php

declare(strict_types=1);

namespace App\Domain\Feature\CompanyDeletion;

use App\Domain\Feature\Exception\CompanyNotFoundException;
use App\Domain\Repository\CompanyRepositoryInterface;
use Symfony\Component\Uid\Ulid;

final readonly class CompanyDeletionService implements CompanyDeletionInterface
{
    public function __construct(
        private CompanyRepositoryInterface $companyRepository,
    ) {
    }

    public function deleteCompany(Ulid $id): void
    {
        $company = $this->companyRepository->findById($id);

        if (null === $company) {
            throw CompanyNotFoundException::create();
        }

        $this->companyRepository->delete($company);
        $this->companyRepository->confirm();
    }
}
