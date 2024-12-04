<?php

declare(strict_types=1);

namespace App\Presentation\Controller\Request;

use App\Presentation\Controller\Request\Exception\RequestValidationException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class ValidatedRequest
{
    protected readonly Request $request;

    public function __construct(RequestStack $requestStack, protected readonly ValidatorInterface $validator)
    {
        $request = $requestStack->getCurrentRequest();

        if (null === $request) {
            throw new \LogicException('Request must exist!');
        }

        $this->request = $request;

        $this->assertRequestIsValid();
    }

    /**
     * @throws RequestValidationException
     */
    abstract protected function assertRequestIsValid(): void;

    protected function getRequestData(): array
    {
        return $this->request->toArray();
    }
}
