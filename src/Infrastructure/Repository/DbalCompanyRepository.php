<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Application\Query\Repository\CompanyRepositoryInterface;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Symfony\Component\Uid\Ulid;

final readonly class DbalCompanyRepository implements CompanyRepositoryInterface
{
    public function __construct(private Connection $connection)
    {
    }

    private function createBaseQueryBuilder(): QueryBuilder
    {
        return $this->connection
            ->createQueryBuilder()
            ->select(
                'c.*, c.created_at AS company_created_at, c.id AS company_company_id',
                'e.*, e.created_at AS employee_created_at, e.id AS employee_id, e.company_id AS employee_company_id'
            )
            ->from('companies', 'c')
            ->leftJoin('c', 'employees', 'e', 'c.id = e.company_id');
    }

    private function normalizeEmployeeRawData(array $rawData): array
    {
        return isset($rawData['employee_id']) ? [
            'id' => $rawData['employee_id'],
            'first_name' => $rawData['first_name'],
            'last_name' => $rawData['last_name'],
            'email' => $rawData['email'],
            'phone' => $rawData['phone'],
            'created_at' => $rawData['employee_created_at'],
        ] : [];
    }

    private function normalizeCompanyRawData(array $rawData): array
    {
        return [
            'id' => $rawData['company_company_id'],
            'name' => $rawData['name'],
            'tax_id' => $rawData['tax_id'],
            'country' => $rawData['country'],
            'address' => $rawData['address'],
            'postal_code' => $rawData['postal_code'],
            'city' => $rawData['city'],
            'created_at' => $rawData['company_created_at'],
        ];
    }

    public function findById(Ulid $id): ?array
    {
        $rawData = $this->createBaseQueryBuilder()
            ->where('c.id = :id')
            ->setParameter('id', $id->toBinary())
            ->executeQuery()
            ->fetchAllAssociative() ?: null;

        if (null === $rawData) {
            return null;
        }

        $companyData = $this->normalizeCompanyRawData($rawData[0]);
        $companyData['employees'] = array_map(fn (array $singleEmployeeRawData) => $this->normalizeEmployeeRawData($singleEmployeeRawData), $rawData);

        return $companyData;
    }

    public function findAll(): array
    {
        $rawData = $this->createBaseQueryBuilder()
            ->orderBy('c.id', 'DESC')
            ->orderBy('e.id', 'DESC')
            ->executeQuery()
            ->fetchAllAssociative() ?: null;

        if (null === $rawData) {
            return [];
        }

        $companies = [];

        foreach ($rawData as $data) {
            if (false === isset($companies[$data['company_company_id']])) {
                $companies[$data['company_company_id']] = $this->normalizeCompanyRawData($data);
                $companies[$data['company_company_id']]['employees'] = [];
            }

            $employeeData = $this->normalizeEmployeeRawData($data);
            if (false === empty($employeeData)) {
                $companies[$data['company_company_id']]['employees'][] = $employeeData;
            }
        }

        return array_values($companies);
    }
}
