<?php

declare(strict_types=1);

namespace App\Application\Command;

use Symfony\Component\Uid\Ulid;

/**
 * @see DeleteCompanyHandler
 */
final readonly class DeleteCompanyCommand implements CommandInterface
{
    public function __construct(public Ulid $id)
    {
    }
}
