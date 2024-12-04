<?php

declare(strict_types=1);

namespace App\Application\Query\Repository\DTO;

final readonly class CompanyData
{
    public function __construct(public array $data)
    {
    }
}
