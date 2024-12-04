<?php

declare(strict_types=1);

namespace App\Application\Command;

use App\Domain\Feature\CompanyDeletion\CompanyDeletionInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class DeleteCompanyHandler
{
    public function __construct(private CompanyDeletionInterface $companyDeletion)
    {
    }

    public function __invoke(DeleteCompanyCommand $command): void
    {
        $this->companyDeletion->deleteCompany($command->id);
    }
}
