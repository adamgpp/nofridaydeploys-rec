<?php

namespace App\Presentation\Controller\Request\Exception;

use Symfony\Component\Validator\ConstraintViolationListInterface;

final class RequestValidationException extends \RuntimeException
{
    private ConstraintViolationListInterface $violations;

    public static function withViolations(ConstraintViolationListInterface $violations): self
    {
        $self = new self('Request validation failed.', 400);
        $self->violations = $violations;

        return $self;
    }

    public function getViolations(): ConstraintViolationListInterface
    {
        return $this->violations;
    }
}
