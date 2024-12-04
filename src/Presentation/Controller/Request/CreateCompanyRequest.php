<?php

declare(strict_types=1);

namespace App\Presentation\Controller\Request;

use App\Presentation\Controller\Request\Exception\RequestValidationException;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Optional;
use Symfony\Component\Validator\Constraints\Required;
use Symfony\Component\Validator\Constraints\Type;

final class CreateCompanyRequest extends ValidatedRequest
{
    private const string COMPANY_NAME = 'companyName';
    private const string COMPANY_TAX_ID = 'companyTaxId';
    private const string COMPANY_ADDRESS = 'companyAddress';
    private const string COMPANY_POSTAL_CODE = 'companyPostalCode';
    private const string COMPANY_CITY = 'companyCity';
    private const string EMPLOYEE_FIRST_NAME = 'employeeFirstName';
    private const string EMPLOYEE_LAST_NAME = 'employeeLastName';
    private const string EMPLOYEE_EMAIL = 'employeeEmail';
    private const string EMPLOYEE_PHONE = 'employeePhone';
    private const string COMPANY_EMPLOYEES = 'employees';

    public function getArray(): array
    {
        return $this->getRequestData();
    }

    protected function assertRequestIsValid(): void
    {
        $constraints = new Collection([
            self::COMPANY_NAME => $this->createRequiredStringConstraints(),
            self::COMPANY_TAX_ID => $this->createRequiredStringConstraints(),
            self::COMPANY_ADDRESS => $this->createRequiredStringConstraints(),
            self::COMPANY_POSTAL_CODE => $this->createRequiredStringConstraints(),
            self::COMPANY_CITY => $this->createRequiredStringConstraints(),
            self::COMPANY_EMPLOYEES => new All([new Collection([
                self::EMPLOYEE_FIRST_NAME => $this->createRequiredStringConstraints(),
                self::EMPLOYEE_LAST_NAME => $this->createRequiredStringConstraints(),
                self::EMPLOYEE_EMAIL => new Required([
                    new NotNull(message: 'Value cannot be null.'),
                    new Type(type: 'string', message: 'Value must be of string type.'),
                    new Email(message: 'Value must be a valid email address.'),
                ]),
                self::EMPLOYEE_PHONE => new Optional([
                    new Type(type: 'string', message: 'Value must be of string type.'),
                    new Length(
                        min: 1,
                        max: 255,
                        minMessage: 'Value must be at least {{ limit }} character long.',
                        maxMessage: 'Value must be at most {{ limit }} characters long.'
                    ),
                ]),
            ])]),
        ]);

        $errors = $this->validator->validate($this->getRequestData(), $constraints);

        if ($errors->count() > 0) {
            throw RequestValidationException::withViolations($errors);
        }
    }

    private function createRequiredStringConstraints(): Required
    {
        return new Required([
            new NotNull(message: 'Value cannot be null.'),
            new Type(type: 'string', message: 'Value must be of string type.'),
            new Length(
                min: 1,
                max: 255,
                minMessage: 'Value must be at least {{ limit }} character long.',
                maxMessage: 'Value must be at most {{ limit }} characters long.'
            ),
        ]);
    }
}
