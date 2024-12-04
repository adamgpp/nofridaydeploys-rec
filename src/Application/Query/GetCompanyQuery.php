<?php

declare(strict_types=1);

namespace App\Application\Query;

use Symfony\Component\Uid\Ulid;

/**
 * @see GetCompanyHandler
 */
final readonly class GetCompanyQuery implements QueryInterface
{
    public function __construct(public Ulid $companyId)
    {
    }
}
