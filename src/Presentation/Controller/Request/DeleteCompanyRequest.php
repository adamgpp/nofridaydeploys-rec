<?php

declare(strict_types=1);

namespace App\Presentation\Controller\Request;

final class DeleteCompanyRequest extends ValidatedRequest
{
    use CompanyRequestValidationTrait;

    protected function assertRequestIsValid(): void
    {
        $this->assertRequestWithIdIsValid();
    }
}
