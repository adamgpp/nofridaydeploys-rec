<?php

declare(strict_types=1);

namespace App\Tests\Integration\Presentation\Controller;

use App\Domain\Entity\Company;
use App\Domain\Entity\Employee;
use App\Domain\Feature\CompanyCreation\DTO\Employee as EmployeeDto;
use App\Domain\Repository\CompanyRepositoryInterface;
use App\Domain\ValueObject\BaseString;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Ulid;

final class UpdateCompanyWebTest extends WebTestCase
{
    private KernelBrowser $client;

    private CompanyRepositoryInterface $companyRepository;

    protected function setUp(): void
    {
        $this->client = self::createClient();

        $this->companyRepository = self::getContainer()->get(CompanyRepositoryInterface::class);
    }

    public function testShouldSuccessfullyUpdateCompany(): void
    {
        $companyId = new Ulid('01JE8SBYGCA4VH44RN9CWD6DT1');
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
            EmployeeDto::fromArray(
                new Ulid('01JE8SN7WFCKDWZHPJM5SCBC8S'),
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

        $requestContent = <<<JSON
            {
              "companyName": "Updated Test Company Name",
              "companyTaxId": "updated-tax-id-123",
              "companyAddress": "Updated Test Street 12",
              "companyPostalCode": "23-456",
              "companyCity": "Updated Test City"
            }
        JSON;

        $this->client->request(
            method: Request::METHOD_PUT,
            uri: "/api/companies/{$company->getId()->toBase32()}",
            content: $requestContent
        );

        self::assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);

        $updatedCompany = $this->companyRepository->findById($companyId);

        self::assertInstanceOf(Company::class, $updatedCompany);

        self::assertCount(1, $updatedCompany->getEmployees());
        self::assertContainsOnlyInstancesOf(Employee::class, $updatedCompany->getEmployees());
        self::assertSame('01JE8SN7WFCKDWZHPJM5SCBC8S', $updatedCompany->getEmployees()[0]->getId()->toBase32());

        self::assertSame('Updated Test Company Name', $updatedCompany->getName()->value);
        self::assertSame('updated-tax-id-123', $updatedCompany->getTaxId()->value);
        self::assertSame('Updated Test Street 12', $updatedCompany->getAddress()->value);
        self::assertSame('23-456', $updatedCompany->getPostalCode()->value);
        self::assertSame('Updated Test City', $updatedCompany->getCity()->value);
    }

    public function testShouldFailWhenCompanyNotFound(): void
    {
        $requestContent = <<<JSON
            {
              "companyName": "Updated Test Company Name",
              "companyTaxId": "updated-tax-id-123",
              "companyAddress": "Updated Test Street 12",
              "companyPostalCode": "23-456",
              "companyCity": "Updated Test City"
            }
        JSON;

        $this->client->request(
            method: Request::METHOD_PUT,
            uri: "/api/companies/01JE97DD43WWNCBVFCFJ9TRW3C",
            content: $requestContent
        );

        self::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testShouldFailWhenOtherCompanyWithGivenTaxIdAlreadyExists(): void
    {
        $existentTaxId = 'existent-tex-id-123';

        $company = new Company(
            new Ulid('01JE8SBYGCA4VH44RN9CWD6DT1'),
            new BaseString('Test Company Name'),
            new BaseString('tax-id-123'),
            new BaseString('Test Street 12'),
            new BaseString('01-234'),
            new BaseString('Test City'),
            new \DateTimeImmutable('2024-12-04 12:12:25'),
        );

        $this->companyRepository->add(new Company(
            new Ulid('01JE8SBYGCA4VH44RN9CWD6DT1'),
            new BaseString('Test Company Name'),
            new BaseString('tax-id-123'),
            new BaseString('Test Street 12'),
            new BaseString('01-234'),
            new BaseString('Test City'),
            new \DateTimeImmutable('2024-12-04 12:12:25'),
        ));
        $this->companyRepository->add(new Company(
            new Ulid('01JE97N5X3M2HQHVK50CXF5QW1'),
            new BaseString('Test Company Name'),
            new BaseString($existentTaxId),
            new BaseString('Test Street 12'),
            new BaseString('01-234'),
            new BaseString('Test City'),
            new \DateTimeImmutable('2024-12-04 12:12:25'),
        ));
        $this->companyRepository->confirm();

        $requestContent = <<<JSON
            {
              "companyName": "Updated Test Company Name",
              "companyTaxId": "$existentTaxId",
              "companyAddress": "Updated Test Street 12",
              "companyPostalCode": "23-456",
              "companyCity": "Updated Test City"
            }
        JSON;

        $this->client->request(
            method: Request::METHOD_PUT,
            uri: "/api/companies/{$company->getId()->toBase32()}",
            content: $requestContent
        );

        self::assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
