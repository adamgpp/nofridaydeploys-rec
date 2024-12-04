<?php

declare(strict_types=1);

namespace App\Application\Command;

use App\Domain\Feature\CompanyUpdate\CompanyUpdateInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class UpdateCompanyHandler
{
    public function __construct(private CompanyUpdateInterface $companyUpdate)
    {
    }

    public function __invoke(UpdateCompanyCommand $command): void
    {
        $this->companyUpdate->updateCompany($command->company);
    }
}
