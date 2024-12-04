<?php

declare(strict_types=1);

namespace App\Application\Query;

use App\Application\Query\Repository\CompanyRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class GetCompanyHandler
{
    public function __construct(private CompanyRepositoryInterface $companyRepository)
    {
    }

    public function __invoke(GetCompanyQuery $query): ?CompanyView
    {
        $company = $this->companyRepository->findById($query->companyId);

        return null === $company ? null : CompanyView::fromDatabaseData($company);
    }
}
