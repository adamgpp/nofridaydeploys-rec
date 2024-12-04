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

final class GetCompaniesWebTest extends WebTestCase
{
    private KernelBrowser $client;

    private CompanyRepositoryInterface $companyRepository;

    protected function setUp(): void
    {
        $this->client = self::createClient();

        $this->companyRepository = self::getContainer()->get(CompanyRepositoryInterface::class);
    }

    public function testShouldSuccessfullyFetchAllCompaniesData(): void
    {
        $company1 = new Company(
            new Ulid('01JE8SBYGCA4VH44RN9CWD6DT1'),
            new BaseString('Test Company Name'),
            new BaseString('tax-id-123'),
            new BaseString('Test Street 12'),
            new BaseString('01-234'),
            new BaseString('Test City'),
            new \DateTimeImmutable('2024-12-04 12:12:25'),
        );
        $company1->assignEmployeesFromDtos(
            Employee::fromArray(
                new Ulid('01JE8SN7WFCKDWZHPJM5SCBC8S'),
                new \DateTimeImmutable('2024-12-04 12:13:25'),
                [
                    'employeeFirstName' => 'Test First Name',
                    'employeeLastName' => 'Test-Last-Name',
                    'employeeEmail' => 'test@email.com',
                    'employeePhone' => null,
                ],
            ),
            Employee::fromArray(
                new Ulid('01JE8SN7WFCKDWZHPJM5SCBC8T'),
                new \DateTimeImmutable('2024-12-04 12:14:25'),
                [
                    'employeeFirstName' => 'Test First Name Two',
                    'employeeLastName' => 'Test-Last-Name-Two',
                    'employeeEmail' => 'test2@email.com',
                    'employeePhone' => '+48 123 456 789',
                ],
            ),
        );
        $company2 = new Company(
            new Ulid('01JE8TQEAJPDZRY8EM901GBK8S'),
            new BaseString('Test Company Name 2'),
            new BaseString('tax-id-123-company-2'),
            new BaseString('Test Street 12'),
            new BaseString('98-765'),
            new BaseString('Test City 2'),
            new \DateTimeImmutable('2024-12-03 12:12:25'),
        );

        $this->companyRepository->add($company1);
        $this->companyRepository->add($company2);
        $this->companyRepository->confirm();

        $this->client->request(Request::METHOD_GET, "/api/companies");

        self::assertResponseStatusCodeSame(Response::HTTP_OK);

        $expectedResponseContent = <<<JSON
            {
              "companies": [
                {
                  "id": "01JE8SBYGCA4VH44RN9CWD6DT1",
                  "name": "Test Company Name",
                  "taxId": "tax-id-123",
                  "address": "Test Street 12",
                  "postalCode": "01-234",
                  "city": "Test City",
                  "createdAt": "2024-12-04 12:12:25",
                  "employees": [
                    {
                      "id": "01JE8SN7WFCKDWZHPJM5SCBC8T",
                      "firstName": "Test First Name Two",
                      "lastName": "Test-Last-Name-Two",
                      "email": "test2@email.com",
                      "phone": "+48 123 456 789",
                      "createdAt": "2024-12-04 12:14:25"
                    },
                    {
                      "id": "01JE8SN7WFCKDWZHPJM5SCBC8S",
                      "firstName": "Test First Name",
                      "lastName": "Test-Last-Name",
                      "email": "test@email.com",
                      "phone": null,
                      "createdAt": "2024-12-04 12:13:25"
                    }
                  ]
                },
                {
                  "id": "01JE8TQEAJPDZRY8EM901GBK8S",
                  "name": "Test Company Name 2",
                  "taxId": "tax-id-123-company-2",
                  "address": "Test Street 12",
                  "postalCode": "98-765",
                  "city": "Test City 2",
                  "createdAt": "2024-12-03 12:12:25",
                  "employees": []
                }
              ]
            }
        JSON;

        self::assertJsonStringEqualsJsonString($expectedResponseContent, $this->client->getResponse()->getContent());
    }

    public function testShouldEmptyDataWhenThereAreNoCompanies(): void
    {
        $this->client->request(Request::METHOD_GET, "/api/companies");

        self::assertResponseStatusCodeSame(Response::HTTP_OK);

        $expectedResponseContent = <<<JSON
            {
              "companies": []
            }
        JSON;

        self::assertJsonStringEqualsJsonString($expectedResponseContent, $this->client->getResponse()->getContent());
    }
}
