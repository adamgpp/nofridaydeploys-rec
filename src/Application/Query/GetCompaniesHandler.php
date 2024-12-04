<?php

declare(strict_types=1);

namespace App\Application\Query;

use App\Application\Query\Repository\CompanyRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class GetCompaniesHandler
{
    public function __construct(private CompanyRepositoryInterface $companyRepository)
    {
    }

    /**
     * @return array<CompanyView>
     */
    public function __invoke(GetCompaniesQuery $query): array
    {
        $companies = $this->companyRepository->findAll();

        return array_map(static fn (array $company) => CompanyView::fromDatabaseData($company), $companies);
    }
}
