<?php

declare(strict_types=1);

namespace App\Presentation\Controller;

use App\Application\Bus\CommandBusInterface;
use App\Application\Bus\QueryBusInterface;
use App\Application\Command\CreateCompanyCommand;
use App\Application\Command\DeleteCompanyCommand;
use App\Application\Command\UpdateCompanyCommand;
use App\Application\Query\GetCompaniesQuery;
use App\Application\Query\GetCompanyQuery;
use App\Domain\Feature\CompanyCreation\DTO\Company as CreateCompanyDto;
use App\Domain\Feature\CompanyUpdate\DTO\Company as UpdateCompanyDto;
use App\Domain\Feature\Exception\CompanyNotFoundException;
use App\Presentation\Controller\Request\CreateCompanyRequest;
use App\Presentation\Controller\Request\DeleteCompanyRequest;
use App\Presentation\Controller\Request\GetCompanyRequest;
use App\Presentation\Controller\Request\UpdateCompanyRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\Ulid;

final class CompanyController extends AbstractController
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
        private readonly QueryBusInterface $queryBus,
    ) {
    }

    #[Route(path: '/api/companies', methods: [Request::METHOD_POST])]
    public function create(CreateCompanyRequest $request): Response
    {
        $companyId = new Ulid();

        $this->commandBus->dispatch(new CreateCompanyCommand(
            CreateCompanyDto::fromArray($companyId, new \DateTimeImmutable(), $request->getArray())
        ));

        return $this->json(['id' => $companyId->toBase32()], Response::HTTP_CREATED);
    }

    #[Route(path: '/api/companies/{id}', methods: [Request::METHOD_GET])]
    public function getOne(GetCompanyRequest $request): Response
    {
        $company = $this->queryBus->dispatch(new GetCompanyQuery(Ulid::fromString($request->getId())));

        return null === $company
            ? throw new NotFoundHttpException() : $this->json(['company' => $company], Response::HTTP_OK);
    }

    #[Route(path: '/api/companies', methods: [Request::METHOD_GET])]
    public function getAll(): Response
    {
        $companies = $this->queryBus->dispatch(new GetCompaniesQuery());

        return $this->json(['companies' => $companies], Response::HTTP_OK);
    }

    #[Route(path: '/api/companies/{id}', methods: [Request::METHOD_DELETE])]
    public function delete(DeleteCompanyRequest $request): Response
    {
        try {
            $this->commandBus->dispatch(new DeleteCompanyCommand(Ulid::fromString($request->getId())));

            return $this->json(null, Response::HTTP_NO_CONTENT);
        } catch (CompanyNotFoundException) {
            throw new NotFoundHttpException();
        }
    }

    #[Route(path: '/api/companies/{id}', methods: [Request::METHOD_PUT])]
    public function update(UpdateCompanyRequest $request): Response
    {
        $companyId = Ulid::fromString($request->getId());

        try {
            $this->commandBus->dispatch(
                new UpdateCompanyCommand(
                    UpdateCompanyDto::fromArray($companyId, $request->getArray())
                )
            );

            return $this->json(null, Response::HTTP_NO_CONTENT);
        } catch (CompanyNotFoundException) {
            throw new NotFoundHttpException();
        }
    }
}
