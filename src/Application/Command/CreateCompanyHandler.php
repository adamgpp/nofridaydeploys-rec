<?php

declare(strict_types=1);

namespace App\Application\Command;

use App\Domain\Feature\CompanyCreation\CompanyCreationInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class CreateCompanyHandler
{
    public function __construct(private CompanyCreationInterface $companyCreation)
    {
    }

    public function __invoke(CreateCompanyCommand $command): void
    {
        $this->companyCreation->createCompany($command->company);
    }
}
