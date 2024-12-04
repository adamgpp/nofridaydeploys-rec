<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\ValueObject;

use App\Domain\ValueObject\BaseString;
use App\Domain\ValueObject\Exception\ValueValidationException;
use PHPUnit\Framework\TestCase;

final class BaseStringTest extends TestCase
{
    public function testShouldCreateValidBaseString(): void
    {
        $value = 'ValidString';
        $baseString = new BaseString($value);

        self::assertInstanceOf(BaseString::class, $baseString);
        self::assertSame($value, $baseString->value);
    }

    public function testThrowsExceptionForTooShortString(): void
    {
        $this->expectException(ValueValidationException::class);
        $this->expectExceptionMessage('BaseString length cannot be lower than 1 character.');

        new BaseString('');
    }

    public function testThrowsExceptionForTooLongString(): void
    {
        $this->expectException(ValueValidationException::class);
        $this->expectExceptionMessage('BaseString length cannot be greater than 255 characters.');

        new BaseString(str_repeat('a', 256));
    }
}
