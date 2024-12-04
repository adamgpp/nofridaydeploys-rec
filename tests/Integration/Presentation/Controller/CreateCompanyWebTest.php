<?php

declare(strict_types=1);

namespace App\Tests\Integration\Presentation\Controller;

use App\Domain\Entity\Company;
use App\Domain\Entity\Employee;
use App\Domain\Repository\CompanyRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Ulid;

final class CreateCompanyWebTest extends WebTestCase
{
    private KernelBrowser $client;

    private CompanyRepositoryInterface $companyRepository;

    protected function setUp(): void
    {
        $this->client = self::createClient();

        $this->companyRepository = self::getContainer()->get(CompanyRepositoryInterface::class);
    }

    public function testShouldSuccessfullyCreateCompanyWithEmployees(): void
    {
        $taxId = uniqid('taxId', true);

        $requestContent = <<<JSON
            {
              "companyName": "Test Company Name",
              "companyTaxId": "$taxId",
              "companyAddress": "Test Street 12",
              "companyPostalCode": "00-098",
              "companyCity": "Test City",
              "employees": [
                {
                  "employeeFirstName": "Test First Name",
                  "employeeLastName": "Test-Last-Name",
                  "employeeEmail": "test@email.com",
                  "employeePhone": "+48123456789"
                }
              ]
            }
        JSON;

        $this->client->request(method: Request::METHOD_POST, uri: '/api/companies', content: $requestContent);

        self::assertResponseStatusCodeSame(Response::HTTP_CREATED);

        $createdCompanyId = strval(json_decode($this->client->getResponse()->getContent(), true)['id'] ?? '');

        $createdCompany = $this->companyRepository->findById(Ulid::fromString($createdCompanyId));

        self::assertInstanceOf(Company::class, $createdCompany);
        self::assertCount(1, $createdCompany->getEmployees());
        self::assertContainsOnlyInstancesOf(Employee::class, $createdCompany->getEmployees());
        self::assertSame('Test First Name', $createdCompany->getEmployees()[0]->getFirstName()->value);
    }
}
