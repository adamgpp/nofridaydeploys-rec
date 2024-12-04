<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\ValueObject;

use App\Domain\ValueObject\Email;
use App\Domain\ValueObject\Exception\ValueValidationException;
use PHPUnit\Framework\TestCase;

final class EmailTest extends TestCase
{
    public function testCanCreateValidEmail(): void
    {
        $emailAddress = 'test@example.com';
        $email = new Email($emailAddress);

        $this->assertInstanceOf(Email::class, $email);
        $this->assertSame($emailAddress, $email->value);
    }

    public function testThrowsExceptionForInvalidEmail(): void
    {
        $this->expectException(ValueValidationException::class);
        $this->expectExceptionMessage('Email value should be a valid email address.');

        new Email('invalid-email');
    }

    public function testThrowsExceptionForEmptyEmail(): void
    {
        $this->expectException(ValueValidationException::class);
        $this->expectExceptionMessage('Email value should be a valid email address.');

        new Email('');
    }
}