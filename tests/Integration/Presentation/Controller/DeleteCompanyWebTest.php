<?php

declare(strict_types=1);

namespace App\Tests\Integration\Presentation\Controller;

use App\Domain\Entity\Company;
use App\Domain\Feature\CompanyCreation\DTO\Employee;
use App\Domain\Repository\CompanyRepositoryInterface;
use App\Domain\ValueObject\BaseString;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Ulid;

final class DeleteCompanyWebTest extends WebTestCase
{
    private KernelBrowser $client;

    private CompanyRepositoryInterface $companyRepository;

    protected function setUp(): void
    {
        $this->client = self::createClient();

        $this->companyRepository = self::getContainer()->get(CompanyRepositoryInterface::class);
    }

    public function testShouldSuccessfullyDeleteCompany(): void
    {
        $companyId = new Ulid('01JE8SBYGCA4VH44RN9CWD6DT1');
        $employeeId = new Ulid('01JE8SN7WFCKDWZHPJM5SCBC8S');
        $company = new Company(
            $companyId,
            new BaseString('Test Company Name'),
            new BaseString('tax-id-123'),
            new BaseString('Test Street 12'),
            new BaseString('01-234'),
            new BaseString('Test City'),
            new \DateTimeImmutable('2024-12-04 12:12:25'),
        );
        $company->assignEmployeesFromDtos(
            Employee::fromArray(
                $employeeId,
                new \DateTimeImmutable('2024-12-04 12:13:25'),
                [
                    'employeeFirstName' => 'Test First Name',
                    'employeeLastName' => 'Test-Last-Name',
                    'employeeEmail' => 'test@email.com',
                    'employeePhone' => null,
                ],
            ),
        );

        $this->companyRepository->add($company);
        $this->companyRepository->confirm();

        $this->client->request(Request::METHOD_DELETE, "/api/companies/{$companyId->toBase32()}");

        self::assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);

        self::assertFalse($this->companyRepository->existsById($companyId));
    }

    public function testShouldFailWhenCopanyNotFound(): void
    {
        $this->client->request(Request::METHOD_DELETE, "/api/companies/01JE92HJCBA55S96215F7RS8W3");

        self::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }
}
